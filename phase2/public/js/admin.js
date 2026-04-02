// GLOBAL VARIABLES
let editingRow = null;

// PAGE INITIALIZATION
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("admin-login-form");
    const loginView = document.getElementById("admin-login-view");
    const dashboardView = document.getElementById("admin-dashboard-view");
    const logoutBtn = document.getElementById("admin-logout-btn");
    const errorMsg = document.getElementById("admin-login-error");
    const adminNav = document.getElementById("admin-nav");
    const sections = document.querySelectorAll(".admin-section");
    const addProductForm = document.getElementById("add-product-form");
    const productList = document.getElementById("admin-product-list");
    const imgUploadInput = document.getElementById("p-img-upload");
    const imgPreviewContainer = document.getElementById("product-images-container");
    
    let uploadWrapper = null;
    if (imgPreviewContainer) {
        uploadWrapper = imgPreviewContainer.querySelector(".image-upload-wrapper");
    }

    const categoryText = document.getElementById("selected-category-text");
    const hiddenCategoryInput = document.getElementById("p-category");
    const categoryDropdownItems = document.querySelectorAll(".category-select-item");

    if (localStorage.getItem("adminLoggedIn") === "true") {
        showDashboard();
    }

    // LOGIN VIEW LOGIC
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const emailInput = document.getElementById("admin-email");
            const passwordInput = document.getElementById("admin-password");
            
            let email = "";
            let password = "";
            if (emailInput) { email = emailInput.value.trim(); }
            if (passwordInput) { password = passwordInput.value.trim(); }

            if (email !== "" && password !== "") {
                localStorage.setItem("adminLoggedIn", "true");
                showDashboard();

                const toastEl = document.getElementById("adminLoginToast");
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            } else {
                if (errorMsg) { errorMsg.classList.remove("d-none"); }
            }
        });
    }

    // LOGOUT LOGIC
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function () {
            localStorage.removeItem("adminLoggedIn");
            showLoginView();

            const toastEl = document.getElementById("adminLogoutToast");
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    }

    // SIDEBAR NAVIGATION
    if (adminNav) {
        adminNav.addEventListener("click", function (e) {
            const link = e.target.closest(".nav-link");
            if (!link || link.classList.contains("disabled")) { return; }

            e.preventDefault();
            const sectionId = link.getAttribute("data-section");

            const allNavLinks = adminNav.querySelectorAll(".nav-link");
            for (let i = 0; i < allNavLinks.length; i++) {
                allNavLinks[i].classList.remove("active");
            }
            link.classList.add("active");

            for (let i = 0; i < sections.length; i++) {
                const section = sections[i];
                if (section.id === "section-" + sectionId) {
                    section.classList.remove("d-none");
                } else {
                    section.classList.add("d-none");
                }
            }
        });
    }

    // CATEGORY DROPDOWN LOGIC
    for (let i = 0; i < categoryDropdownItems.length; i++) {
        const item = categoryDropdownItems[i];
        item.addEventListener("click", function (e) {
            e.preventDefault();
            const value = item.getAttribute("data-value");
            if (categoryText) { categoryText.innerText = value; }
            if (hiddenCategoryInput) { hiddenCategoryInput.value = value; }
        });
    }

    // IMAGE UPLOAD LOGIC
    if (uploadWrapper) {
        uploadWrapper.addEventListener("click", function (e) {
            if (e.target.closest("label") || e.target === imgUploadInput) { return; }
            if (imgUploadInput) { imgUploadInput.click(); }
        });
    }

    if (imgUploadInput) {
        imgUploadInput.addEventListener("change", function (e) {
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function (event) {
                    const preview = document.createElement("div");
                    preview.className = "product-image-preview";
                    preview.innerHTML = `
                        <img src="${event.target.result}">
                        <button type="button" class="remove-img-btn">×</button>
                    `;

                    const removeBtn = preview.querySelector(".remove-img-btn");
                    if (removeBtn) {
                        removeBtn.addEventListener("click", function () {
                            preview.remove();
                        });
                    }

                    if (imgPreviewContainer && uploadWrapper) {
                        imgPreviewContainer.insertBefore(preview, uploadWrapper);
                    }
                };
                reader.readAsDataURL(file);
            }
            imgUploadInput.value = "";
        });
    }

    // PRODUCT LIST ACTIONS (EDIT/DELETE)
    if (productList) {
        productList.addEventListener("click", function (e) {
            const deleteBtn = e.target.closest(".btn-delete-icon");
            if (deleteBtn) {
                const row = deleteBtn.closest("tr");
                if (row) { row.classList.add("d-none"); }
                return;
            }

            const editBtn = e.target.closest(".btn-edit-icon");
            if (editBtn) {
                const row = editBtn.closest("tr");
                if (row) {
                    editingRow = row;
                    const nameCell = row.querySelector("td:nth-child(2)").innerText.trim();
                    const categoryCell = row.querySelector("td:nth-child(3)").innerText.trim();
                    const priceCell = row.querySelector("td:nth-child(4)").innerText.trim().replace(" €", "").replace(",", ".");
                    const stockCellText = row.querySelector("td:nth-child(5)").innerText;
                    
                    let stockCount = "";
                    const stockMatch = stockCellText.match(/\d+/);
                    if (stockMatch) { stockCount = stockMatch[0]; }

                    const descAttr = row.getAttribute("data-desc") || "";
                    const imagesAttr = row.getAttribute("data-images");

                    const nameInput = document.getElementById("p-name");
                    const priceInput = document.getElementById("p-price");
                    const stockInput = document.getElementById("p-stock");
                    const descInput = document.getElementById("p-desc");

                    if (nameInput) { nameInput.value = nameCell; }
                    if (priceInput) { priceInput.value = priceCell; }
                    if (stockInput) { stockInput.value = stockCount; }
                    if (descInput) { descInput.value = descAttr; }
                    
                    if (categoryText) { categoryText.innerText = categoryCell; }
                    if (hiddenCategoryInput) { hiddenCategoryInput.value = categoryCell; }
                    
                    // Clear existing previews
                    if (imgPreviewContainer) {
                        const allPreviews = imgPreviewContainer.querySelectorAll(".product-image-preview");
                        for (let i = 0; i < allPreviews.length; i++) {
                            allPreviews[i].remove();
                        }
                    }
                    
                    // Handle images preview for edit mode
                    if (imagesAttr) {
                        try {
                            const imageUrls = JSON.parse(decodeURIComponent(imagesAttr));
                            for (let i = 0; i < imageUrls.length; i++) {
                                const src = imageUrls[i];
                                const preview = document.createElement("div");
                                preview.className = "product-image-preview";
                                preview.innerHTML = `
                                    <img src="${src}">
                                    <button type="button" class="remove-img-btn">×</button>
                                `;
                                const removeBtn = preview.querySelector(".remove-img-btn");
                                if (removeBtn) {
                                    removeBtn.addEventListener("click", function () { preview.remove(); });
                                }
                                if (imgPreviewContainer && uploadWrapper) {
                                    imgPreviewContainer.insertBefore(preview, uploadWrapper);
                                }
                            }
                        } catch (err) { console.error(err); }
                    } else {
                        // Fallback to thumbnail
                        const thumbImg = row.querySelector(".product-thumb-sm img");
                        if (thumbImg) {
                            const preview = document.createElement("div");
                            preview.className = "product-image-preview";
                            preview.innerHTML = `
                                <img src="${thumbImg.src}">
                                <button type="button" class="remove-img-btn">×</button>
                            `;
                            const removeBtn = preview.querySelector(".remove-img-btn");
                            if (removeBtn) {
                                removeBtn.addEventListener("click", function () { preview.remove(); });
                            }
                            if (imgPreviewContainer && uploadWrapper) {
                                imgPreviewContainer.insertBefore(preview, uploadWrapper);
                            }
                        }
                    }

                    const modalLabel = document.getElementById("addProductModalLabel");
                    if (modalLabel) { modalLabel.innerText = "Upraviť produkt"; }
                    
                    const modalEl = document.getElementById("addProductModal");
                    if (modalEl) {
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (!modal) { modal = new bootstrap.Modal(modalEl); }
                        modal.show();
                    }
                }
            }
        });
    }

    // ADD PRODUCT MODAL OPENING
    const addProductBtn = document.querySelector("[data-bs-target='#addProductModal']");
    if (addProductBtn) {
        addProductBtn.addEventListener("click", function () {
            editingRow = null;
            const modalLabel = document.getElementById("addProductModalLabel");
            if (modalLabel) { modalLabel.innerText = "Pridať nový produkt"; }
            if (addProductForm) { addProductForm.reset(); }
            if (categoryText) { categoryText.innerText = "Vybrať kategóriu..."; }
            if (hiddenCategoryInput) { hiddenCategoryInput.value = ""; }
            
            if (imgPreviewContainer) {
                const allPreviews = imgPreviewContainer.querySelectorAll(".product-image-preview");
                for (let i = 0; i < allPreviews.length; i++) {
                    allPreviews[i].remove();
                }
            }
        });
    }

    // FORM SUBMISSION (ADD/EDIT RELOAD)
    if (addProductForm) {
        addProductForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const name = document.getElementById("p-name").value;
            const category = document.getElementById("p-category").value;
            const price = document.getElementById("p-price").value;
            const stock = document.getElementById("p-stock").value;
            const desc = document.getElementById("p-desc").value;
            
            let previews = [];
            if (imgPreviewContainer) {
                previews = imgPreviewContainer.querySelectorAll(".product-image-preview");
            }

            if (previews.length < 2) {
                const toastEl = document.getElementById("adminLoginToast");
                if (toastEl) {
                    const toastBody = toastEl.querySelector(".toast-body");
                    if (toastBody) { toastBody.innerText = "Prosím pridajte aspoň 2 fotografie."; }
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
                return;
            }

            const imageUrls = [];
            for (let i = 0; i < previews.length; i++) {
                const img = previews[i].querySelector("img");
                if (img) { imageUrls.push(img.src); }
            }

            if (editingRow) {
                // UPDATE ROW
                const nameDiv = editingRow.querySelector("td:nth-child(2) div");
                if (nameDiv) {
                    let textNodeUpdated = false;
                    for (let i = 0; i < nameDiv.childNodes.length; i++) {
                        const node = nameDiv.childNodes[i];
                        if (node.nodeType === 3 && node.nodeValue.trim().length > 0) { // Node.TEXT_NODE
                            node.nodeValue = " " + name;
                            textNodeUpdated = true;
                        }
                    }
                    if (textNodeUpdated === false) {
                        nameDiv.appendChild(document.createTextNode(" " + name));
                    }
                }
                editingRow.querySelector("td:nth-child(3)").innerText = category;
                editingRow.querySelector("td:nth-child(4)").innerText = parseFloat(price).toFixed(2) + " €";
                editingRow.querySelector("td:nth-child(5)").innerHTML = '<span class="badge bg-success">Na sklade (' + parseInt(stock, 10) + 'ks)</span>';
                
                editingRow.setAttribute("data-desc", desc);
                editingRow.setAttribute("data-images", encodeURIComponent(JSON.stringify(imageUrls)));

                if (imageUrls.length > 0) {
                    const thumbImg = editingRow.querySelector(".product-thumb-sm img");
                    if (thumbImg) {
                        thumbImg.src = imageUrls[0];
                    } else {
                        const thumbDiv = editingRow.querySelector(".product-thumb-sm");
                        if (thumbDiv) {
                            thumbDiv.innerHTML = '<img src="' + imageUrls[0] + '" style="width:100%; height:100%; object-fit:cover;">';
                            thumbDiv.classList.add("overflow-hidden");
                        }
                    }
                }
            } else {
                // ADD ROW
                const newRow = document.createElement("tr");
                newRow.setAttribute("data-desc", desc);
                newRow.setAttribute("data-images", encodeURIComponent(JSON.stringify(imageUrls)));
                newRow.innerHTML = `
                    <td class="ps-4">#${Math.floor(1000 + Math.random() * 9000)}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="product-thumb-sm bg-prussian-blue rounded overflow-hidden">
                               <img src="${imageUrls[0]}" style="width:100%; height:100%; object-fit:cover;">
                            </div>
                            ${name}
                        </div>
                    </td>
                    <td>${category}</td>
                    <td>${parseFloat(price).toFixed(2)} €</td>
                    <td><span class="badge bg-success">Na sklade (${parseInt(stock, 10)}ks)</span></td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-edit-icon" title="Upraviť"><img src="assets/pencil.png" class="icon-xs icon-white"></button>
                            <button class="btn btn-delete-icon" title="Vymazať"><img src="assets/trash.png" class="icon-xs icon-white"></button>
                        </div>
                    </td>
                `;
                if (productList) { productList.prepend(newRow); }
            }

            // CLOSE MODAL
            const modalEl = document.getElementById("addProductModal");
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) { modal.hide(); }
            }

            // RESET FORM
            if (addProductForm) { addProductForm.reset(); }
            if (imgPreviewContainer) {
                const allPreviews = imgPreviewContainer.querySelectorAll(".product-image-preview");
                for (let i = 0; i < allPreviews.length; i++) {
                    allPreviews[i].remove();
                }
            }

            if (categoryText) { categoryText.innerText = "Vybrať kategóriu..."; }
            if (hiddenCategoryInput) { hiddenCategoryInput.value = ""; }

            // NOTIFICATION
            const toastEl = document.getElementById("adminLoginToast");
            if (toastEl) {
                const toastBody = toastEl.querySelector(".toast-body");
                if (toastBody) {
                    if (editingRow) {
                        toastBody.innerText = "Produkt bol upravený!";
                    } else {
                        toastBody.innerText = "Produkt bol úspešne pridaný!";
                    }
                }
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
            editingRow = null;
        });
    }

    // DASHBOARD DISPLAY HELPERS
    function showDashboard() {
        if (loginView && dashboardView) {
            loginView.classList.add("d-none");
            loginView.classList.remove("d-flex");
            dashboardView.classList.remove("d-none");
            if (errorMsg) { errorMsg.classList.add("d-none"); }
            if (loginForm) { loginForm.reset(); }
        }
    }

    function showLoginView() {
        if (loginView && dashboardView) {
            loginView.classList.add("d-flex");
            loginView.classList.remove("d-none");
            dashboardView.classList.add("d-none");
        }
    }
});
