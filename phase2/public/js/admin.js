// Admin page UI: product list, search, add, edit, delete (calls Laravel JSON API).
let uploadedImages = [];
let editUploadedImages = [];
let editPendingRemoveIds = [];
let editProductId = null;

// Build image URL for <img src> (API posiela image_url z modelu; inak skladáme z url stĺpca).
function adminProductImageUrl(obrazokOrUrl) {
    if (obrazokOrUrl && typeof obrazokOrUrl === "object") {
        if (obrazokOrUrl.image_url) {
            return obrazokOrUrl.image_url;
        }
        obrazokOrUrl = obrazokOrUrl.url;
    }
    var url = obrazokOrUrl || "";
    if (!url) {
        return "/assets/grapes_white_tray.png";
    }
    if (url.indexOf("http://") === 0 || url.indexOf("https://") === 0) {
        return url;
    }
    if (url.indexOf("assets/") === 0) {
        return "/" + url;
    }
    return "/storage/" + String(url).replace(/^\/+/, "");
}

// Read CSRF token from the layout meta tag.
function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        return "";
    }
    return meta.getAttribute("content");
}

// Run fetch with cookies so Laravel session auth works.
function adminFetch(url, options) {
    var opts = options || {};
    if (!opts.credentials) {
        opts.credentials = "same-origin";
    }
    if (!opts.headers) {
        opts.headers = {};
    }
    if (!opts.headers["Accept"]) {
        opts.headers["Accept"] = "application/json";
    }
    if (!opts.headers["X-Requested-With"]) {
        opts.headers["X-Requested-With"] = "XMLHttpRequest";
    }
    if (!opts.headers["X-CSRF-TOKEN"] && !opts.headers["x-csrf-token"]) {
        opts.headers["X-CSRF-TOKEN"] = getCsrfToken();
    }
    return fetch(url, opts);
}

document.addEventListener("DOMContentLoaded", function () {
    var adminNav = document.getElementById("admin-nav");
    var sections = document.querySelectorAll(".admin-section");
    var addProductForm = document.getElementById("add-product-form");
    var imgUploadInput = document.getElementById("p-img-upload");
    var imgPreviewContainer = document.getElementById("product-images-container");
    var searchInput = document.getElementById("admin-product-search");
    var searchBtn = document.getElementById("admin-search-btn");

    var categoryText = document.getElementById("selected-category-text");
    var hiddenCategoryInput = document.getElementById("p-category");
    var categoryDropdownItems = document.querySelectorAll(".category-select-item");

    var editProductForm = document.getElementById("edit-product-form");
    var eImgUpload = document.getElementById("e-img-upload");
    var categoryTextEdit = document.getElementById("selected-category-text-edit");
    var hiddenCategoryEdit = document.getElementById("e-category");
    var categoryEditItems = document.querySelectorAll(".category-select-item-edit");

    if (adminNav) {
        loadProducts();
    }

    if (adminNav) {
        adminNav.addEventListener("click", function (e) {
            var link = e.target.closest(".nav-link");
            if (!link || link.classList.contains("disabled")) {
                return;
            }

            e.preventDefault();
            var sectionId = link.getAttribute("data-section");

            var allNavLinks = adminNav.querySelectorAll(".nav-link");
            for (var i = 0; i < allNavLinks.length; i++) {
                allNavLinks[i].classList.remove("active");
            }
            link.classList.add("active");

            for (var j = 0; j < sections.length; j++) {
                var section = sections[j];
                if (section.id === "section-" + sectionId) {
                    section.classList.remove("d-none");
                    if (sectionId === "products") {
                        loadProducts();
                    }
                } else {
                    section.classList.add("d-none");
                }
            }
        });
    }

    for (var c = 0; c < categoryDropdownItems.length; c++) {
        categoryDropdownItems[c].addEventListener("click", function (e) {
            e.preventDefault();
            var value = this.getAttribute("data-value");
            if (categoryText) {
                categoryText.innerText = value;
            }
            if (hiddenCategoryInput) {
                hiddenCategoryInput.value = value;
            }
        });
    }

    for (var ce = 0; ce < categoryEditItems.length; ce++) {
        categoryEditItems[ce].addEventListener("click", function (e) {
            e.preventDefault();
            var val = this.getAttribute("data-value");
            if (categoryTextEdit) {
                categoryTextEdit.innerText = val;
            }
            if (hiddenCategoryEdit) {
                hiddenCategoryEdit.value = val;
            }
        });
    }

    if (imgUploadInput) {
        imgUploadInput.addEventListener("change", function (e) {
            var files = Array.from(e.target.files);
            uploadedImages = uploadedImages.concat(files);

            files.forEach(function (file) {
                if (file.type.indexOf("image/") !== 0) {
                    return;
                }
                (function (oneFile) {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        createImagePreview(
                            event.target.result,
                            oneFile,
                            "product-images-container",
                            uploadedImages
                        );
                    };
                    reader.readAsDataURL(oneFile);
                })(file);
            });
            imgUploadInput.value = "";
        });
    }

    if (eImgUpload) {
        eImgUpload.addEventListener("change", function (e) {
            var newFiles = Array.from(e.target.files);
            editUploadedImages = editUploadedImages.concat(newFiles);
            var wrap = document.getElementById("edit-new-images-container");
            var uploadBtn = wrap ? wrap.querySelector(".image-upload-wrapper") : null;

            newFiles.forEach(function (nf) {
                if (nf.type.indexOf("image/") !== 0) {
                    return;
                }
                (function (oneFile) {
                    var r = new FileReader();
                    r.onload = function (ev) {
                        var previewWrapper = document.createElement("div");
                        previewWrapper.className =
                            "product-image-preview p-2 border border-dusk-blue rounded position-relative";
                        var img = document.createElement("img");
                        img.src = ev.target.result;
                        img.className = "img-fluid";
                        img.style.height = "80px";
                        img.style.width = "80px";
                        img.style.objectFit = "cover";
                        var removeBtn = document.createElement("button");
                        removeBtn.type = "button";
                        removeBtn.className = "btn btn-sm btn-danger position-absolute top-0 end-0 m-1";
                        removeBtn.innerHTML = "×";
                        removeBtn.onclick = function () {
                            previewWrapper.remove();
                            var ix = editUploadedImages.indexOf(oneFile);
                            if (ix > -1) {
                                editUploadedImages.splice(ix, 1);
                            }
                        };
                        previewWrapper.appendChild(img);
                        previewWrapper.appendChild(removeBtn);
                        if (wrap && uploadBtn) {
                            wrap.insertBefore(previewWrapper, uploadBtn);
                        }
                    };
                    r.readAsDataURL(oneFile);
                })(nf);
            });
            eImgUpload.value = "";
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener("click", function () {
            loadProducts();
        });
    }

    if (searchInput) {
        searchInput.addEventListener("keyup", function (e) {
            if (e.key === "Enter") {
                loadProducts();
            }
        });
    }

    if (addProductForm) {
        addProductForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleProductSubmit();
        });
    }

    if (editProductForm) {
        editProductForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleEditProductSubmit();
        });
    }

    setupDeleteModal();
    setupInputLimits();
    setupEditInputLimits();
});

function setupInputLimits() {
    var priceInput = document.getElementById("p-price");
    var stockInput = document.getElementById("p-stock");

    if (priceInput) {
        priceInput.addEventListener("input", function (e) {
            var value = e.target.value;
            if (value.length > 6) {
                e.target.value = value.substring(0, 6);
            }
            var numericValue = parseFloat(e.target.value);
            if (!isNaN(numericValue) && numericValue > 999.99) {
                e.target.value = "999.99";
            }
        });
    }

    if (stockInput) {
        stockInput.addEventListener("input", function (e) {
            var value = e.target.value;
            if (value.length > 3) {
                e.target.value = value.substring(0, 3);
            }
            var nv = parseInt(e.target.value, 10);
            if (!isNaN(nv) && nv > 999) {
                e.target.value = "999";
            }
        });
    }
}

function setupEditInputLimits() {
    var priceInput = document.getElementById("e-price");
    var stockInput = document.getElementById("e-stock");

    if (priceInput) {
        priceInput.addEventListener("input", function (e) {
            var value = e.target.value;
            if (value.length > 6) {
                e.target.value = value.substring(0, 6);
            }
            var numericValue = parseFloat(e.target.value);
            if (!isNaN(numericValue) && numericValue > 999.99) {
                e.target.value = "999.99";
            }
        });
    }

    if (stockInput) {
        stockInput.addEventListener("input", function (e) {
            var value = e.target.value;
            if (value.length > 3) {
                e.target.value = value.substring(0, 3);
            }
            var nv = parseInt(e.target.value, 10);
            if (!isNaN(nv) && nv > 999) {
                e.target.value = "999";
            }
        });
    }
}

function loadProducts() {
    var searchInput = document.getElementById("admin-product-search");
    var searchValue = searchInput ? searchInput.value : "";

    adminFetch("/api/admin/products?search=" + encodeURIComponent(searchValue))
        .then(function (response) {
            if (response.status === 403 || response.status === 401) {
                showErrorToast("Nemáte oprávnenie načítať produkty. Prihláste sa ako administrátor.");
                throw new Error("auth");
            }
            if (!response.ok) {
                throw new Error("http");
            }
            return response.json();
        })
        .then(function (data) {
            displayProducts(data.products);
        })
        .catch(function (error) {
            if (error && error.message === "auth") {
                return;
            }
            if (error && error.message === "http") {
                showErrorToast("Nepodarilo sa načítať produkty (skontrolujte prihlásenie).");
                return;
            }
            console.error("Error loading products:", error);
            showErrorToast("Nepodarilo sa načítať produkty");
        });
}

function displayProducts(products) {
    var productList = document.getElementById("admin-product-list");
    if (!productList) {
        return;
    }

    productList.innerHTML = "";

    if (!products || products.length === 0) {
        productList.innerHTML =
            '<tr><td colspan="6" class="text-center py-4 text-white-50">Žiadne produkty neboli nájdené</td></tr>';
        return;
    }

    for (var i = 0; i < products.length; i++) {
        productList.appendChild(createProductRow(products[i]));
    }
}

function createProductRow(product) {
    var row = document.createElement("tr");
    row.innerHTML =
        '<td class="ps-4">#' +
        product.id +
        "</td>" +
        "<td>" +
        product.name +
        "</td>" +
        "<td>" +
        (product.kategoria ? product.kategoria.name : "Neznáma") +
        "</td>" +
        "<td>" +
        parseFloat(product.price).toFixed(2) +
        " €</td>" +
        '<td><span class="badge bg-success">Na sklade (' +
        parseInt(product.quantity, 10) +
        "ks)</span></td>" +
        '<td class="text-end pe-4">' +
        '<div class="d-flex justify-content-end gap-2">' +
        '<button type="button" class="btn btn-edit-icon" title="Upraviť" data-edit-id="' +
        product.id +
        '">' +
        '<img src="/assets/pencil.png" class="icon-xs icon-white">' +
        "</button>" +
        '<button type="button" class="btn btn-delete-icon" title="Vymazať" data-delete-id="' +
        product.id +
        '">' +
        '<img src="/assets/trash.png" class="icon-xs icon-white">' +
        "</button>" +
        "</div>" +
        "</td>";

    var editBtn = row.querySelector("[data-edit-id]");
    if (editBtn) {
        editBtn.addEventListener("click", function () {
            editProduct(parseInt(editBtn.getAttribute("data-edit-id"), 10));
        });
    }
    var delBtn = row.querySelector("[data-delete-id]");
    if (delBtn) {
        delBtn.addEventListener("click", function () {
            var pid = delBtn.getAttribute("data-delete-id");
            confirmDelete(pid);
        });
    }

    return row;
}

function createImagePreview(imageSrc, file, containerId, imagesArray) {
    var imgPreviewContainer = document.getElementById(containerId);
    if (!imgPreviewContainer) {
        return;
    }

    var previewWrapper = document.createElement("div");
    previewWrapper.className = "product-image-preview p-2 border border-dusk-blue rounded position-relative";

    var img = document.createElement("img");
    img.src = imageSrc;
    img.className = "img-fluid";
    img.style.height = "80px";
    img.style.width = "80px";
    img.style.objectFit = "cover";

    var removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "btn btn-sm btn-danger position-absolute top-0 end-0 m-1";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = function () {
        previewWrapper.remove();
        var index = imagesArray.indexOf(file);
        if (index > -1) {
            imagesArray.splice(index, 1);
        }
    };

    previewWrapper.appendChild(img);
    previewWrapper.appendChild(removeBtn);

    var uploadBtn = imgPreviewContainer.querySelector(".image-upload-wrapper");
    if (uploadBtn) {
        imgPreviewContainer.insertBefore(previewWrapper, uploadBtn);
    }
}

function handleProductSubmit() {
    var name = document.getElementById("p-name").value;
    var description = document.getElementById("p-desc").value;
    var category = document.getElementById("p-category").value;
    var price = document.getElementById("p-price").value;
    var stock = document.getElementById("p-stock").value;

    if (!name) {
        showErrorToast("Názov produktu je povinný");
        return;
    }
    if (!description) {
        showErrorToast("Popis produktu je povinný");
        return;
    }
    if (!category || category.trim() === "") {
        showErrorToast("Prosím vyberte kategóriu");
        return;
    }
    if (!price || parseFloat(price) <= 0) {
        showErrorToast("Cena musí byť kladné číslo");
        return;
    }
    if (!stock || parseInt(stock, 10) < 0) {
        showErrorToast("Množstvo na sklade musí byť nezáporné číslo");
        return;
    }
    if (uploadedImages.length < 2) {
        showErrorToast("Minimálne 2 obrázky sú povinné");
        return;
    }

    var formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    formData.append("category", category);
    formData.append("price", price);
    formData.append("stock", stock);

    for (var i = 0; i < uploadedImages.length; i++) {
        formData.append("images[]", uploadedImages[i]);
    }

    adminFetch("/api/admin/products", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            if (!response.ok) {
                return response.text().then(function (text) {
                    throw new Error("HTTP " + response.status + ": " + text);
                });
            }
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                showSuccessToast(data.message);
                resetProductForm();
                loadProducts();
                var modalEl = document.getElementById("addProductModal");
                if (modalEl) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }
            } else {
                if (data.errors) {
                    var msg = "Chyby validácie:\n";
                    for (var field in data.errors) {
                        msg += data.errors[field][0] + "\n";
                    }
                    showErrorToast(msg);
                } else {
                    showErrorToast(data.message || "Neznáma chyba");
                }
            }
        })
        .catch(function (error) {
            console.error("Error adding product:", error);
            showErrorToast("Nastala chyba pri pridávaní produktu: " + error.message);
        });
}

function resetProductForm() {
    var addProductForm = document.getElementById("add-product-form");
    if (addProductForm) {
        addProductForm.reset();
    }
    var categoryText = document.getElementById("selected-category-text");
    var hiddenCategoryInput = document.getElementById("p-category");
    if (categoryText) {
        categoryText.innerText = "Vybrať kategóriu...";
    }
    if (hiddenCategoryInput) {
        hiddenCategoryInput.value = "";
    }
    var imgPreviewContainer = document.getElementById("product-images-container");
    if (imgPreviewContainer) {
        var previews = imgPreviewContainer.querySelectorAll(".product-image-preview");
        for (var i = 0; i < previews.length; i++) {
            previews[i].remove();
        }
    }
    uploadedImages = [];
}

function setupDeleteModal() {
    var confirmDeleteBtn = document.getElementById("confirm-delete-btn");
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", function () {
            var productId = document.getElementById("delete-product-id").value;
            deleteProduct(productId);
        });
    }
}

function confirmDelete(productId) {
    var modalEl = document.getElementById("deleteProductModal");
    var productIdEl = document.getElementById("delete-product-id");
    if (productIdEl) {
        productIdEl.value = productId;
    }
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function deleteProduct(productId) {
    adminFetch("/api/admin/products/" + productId, {
        method: "DELETE",
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                showSuccessToast(data.message);
                loadProducts();
                var modalEl = document.getElementById("deleteProductModal");
                if (modalEl) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }
            } else {
                showErrorToast(data.message);
            }
        })
        .catch(function (error) {
            console.error("Error deleting product:", error);
            showErrorToast("Nastala chyba pri mazaní produktu");
        });
}

// Open edit modal and load product JSON from the server.
function editProduct(productId) {
    editProductId = productId;
    editPendingRemoveIds = [];
    editUploadedImages = [];

    adminFetch("/api/admin/products/" + productId)
        .then(function (response) {
            if (!response.ok) {
                throw new Error("HTTP " + response.status);
            }
            return response.json();
        })
        .then(function (data) {
            var p = data.product;
            document.getElementById("edit-product-id").value = String(p.id);
            document.getElementById("e-name").value = p.name || "";
            document.getElementById("e-desc").value = p.description || "";
            document.getElementById("e-price").value = p.price;
            document.getElementById("e-stock").value = parseInt(p.quantity, 10);

            var catName = p.kategoria ? p.kategoria.name : "";
            document.getElementById("selected-category-text-edit").innerText = catName || "Vybrať kategóriu...";
            document.getElementById("e-category").value = catName;

            var existingWrap = document.getElementById("edit-existing-images");
            existingWrap.innerHTML = "";
            var imgs = p.obrazky || [];
            for (var i = 0; i < imgs.length; i++) {
                existingWrap.appendChild(createExistingImageThumb(imgs[i]));
            }

            var newWrap = document.getElementById("edit-new-images-container");
            var previews = newWrap.querySelectorAll(".product-image-preview");
            for (var j = 0; j < previews.length; j++) {
                previews[j].remove();
            }

            var modalEl = document.getElementById("editProductModal");
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        })
        .catch(function (err) {
            console.error(err);
            showErrorToast("Nepodarilo sa načítať produkt na úpravu");
        });
}

// One existing DB image row with remove toggle.
function createExistingImageThumb(obrazok) {
    var wrap = document.createElement("div");
    wrap.className = "product-image-preview p-2 border border-dusk-blue rounded position-relative";
    wrap.setAttribute("data-image-id", String(obrazok.id));

    var img = document.createElement("img");
    img.src = obrazok.image_url || adminProductImageUrl(obrazok);
    img.className = "img-fluid";
    img.style.height = "80px";
    img.style.width = "80px";
    img.style.objectFit = "cover";

    var btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-sm btn-danger position-absolute top-0 end-0 m-1";
    btn.innerHTML = "×";
    btn.onclick = function () {
        toggleRemoveExistingImage(obrazok.id, wrap, btn);
    };

    wrap.appendChild(img);
    wrap.appendChild(btn);
    return wrap;
}

// Mark image for removal (or undo) before saving the edit form.
function toggleRemoveExistingImage(imageId, wrap, btn) {
    var id = parseInt(imageId, 10);
    var ix = editPendingRemoveIds.indexOf(id);
    if (ix === -1) {
        editPendingRemoveIds.push(id);
        wrap.style.opacity = "0.35";
        btn.innerHTML = "↩";
    } else {
        editPendingRemoveIds.splice(ix, 1);
        wrap.style.opacity = "1";
        btn.innerHTML = "×";
    }
}

function handleEditProductSubmit() {
    if (!editProductId) {
        showErrorToast("Chýba ID produktu");
        return;
    }

    var name = document.getElementById("e-name").value;
    var description = document.getElementById("e-desc").value;
    var category = document.getElementById("e-category").value;
    var price = document.getElementById("e-price").value;
    var stock = document.getElementById("e-stock").value;

    if (!name || !description) {
        showErrorToast("Vyplňte názov a popis");
        return;
    }
    if (!category || category.trim() === "") {
        showErrorToast("Vyberte kategóriu");
        return;
    }

    var existingCount = document.querySelectorAll("#edit-existing-images .product-image-preview").length;
    var kept = existingCount - editPendingRemoveIds.length;
    if (kept + editUploadedImages.length < 2) {
        showErrorToast("Produkt musí mať aspoň 2 fotografie. Zrušte odstránenie alebo pridajte nové súbory.");
        return;
    }

    var formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    formData.append("category", category);
    formData.append("price", price);
    formData.append("stock", stock);

    for (var r = 0; r < editPendingRemoveIds.length; r++) {
        formData.append("remove_image_ids[]", String(editPendingRemoveIds[r]));
    }
    for (var u = 0; u < editUploadedImages.length; u++) {
        formData.append("images[]", editUploadedImages[u]);
    }

    adminFetch("/api/admin/products/" + editProductId + "/update", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, status: response.status, data: data };
            });
        })
        .then(function (result) {
            if (result.ok && result.data.success) {
                showSuccessToast(result.data.message);
                var modalEl = document.getElementById("editProductModal");
                if (modalEl) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }
                editPendingRemoveIds = [];
                editUploadedImages = [];
                editProductId = null;
                loadProducts();
            } else {
                var d = result.data;
                if (d && d.errors) {
                    var keys = Object.keys(d.errors);
                    if (keys.length > 0) {
                        showErrorToast(d.errors[keys[0]][0]);
                    } else {
                        showErrorToast(d.message || "Chyba pri ukladaní");
                    }
                } else {
                    showErrorToast((d && d.message) || "Chyba pri ukladaní");
                }
            }
        })
        .catch(function (error) {
            console.error(error);
            showErrorToast("Nastala chyba pri úprave produktu");
        });
}

function showSuccessToast(message) {
    showToast(message, "success");
}

function showErrorToast(message) {
    showToast(message, "error");
}

function showToast(message, type) {
    var toastEl = document.getElementById("adminNotificationToast");
    if (!toastEl) {
        var toastContainer = document.querySelector(".toast-container");
        if (toastContainer) {
            toastEl = document.createElement("div");
            toastEl.id = "adminNotificationToast";
            toastEl.className = "toast align-items-center border-0";
            toastEl.setAttribute("role", "alert");
            toastEl.setAttribute("aria-live", "assertive");
            toastEl.setAttribute("aria-atomic", "true");
            toastEl.innerHTML =
                '<div class="d-flex">' +
                '<div class="toast-body"></div>' +
                '<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                "</div>";
            toastContainer.appendChild(toastEl);
        }
    }

    if (toastEl) {
        var toastBody = toastEl.querySelector(".toast-body");
        if (toastBody) {
            toastBody.textContent = message;
        }
        toastEl.className = "toast align-items-center border-0";
        if (type === "success") {
            toastEl.classList.add("toast-success-custom");
        } else {
            toastEl.classList.add("bg-danger", "text-white");
        }
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}
