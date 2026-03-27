document.addEventListener("DOMContentLoaded", () => {
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
    const uploadWrapper = imgPreviewContainer?.querySelector(".image-upload-wrapper");

    // Bootstrap Custom Dropdown elements
    const categoryText = document.getElementById("selected-category-text");
    const hiddenCategoryInput = document.getElementById("p-category");
    const categoryDropdownItems = document.querySelectorAll(".category-select-item");

    if (localStorage.getItem("adminLoggedIn") === "true") {
        showDashboard();
    }

    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const email = document.getElementById("admin-email").value.trim();
            const password = document.getElementById("admin-password").value.trim();

            if (email && password) {
                localStorage.setItem("adminLoggedIn", "true");
                showDashboard();

                const toastEl = document.getElementById("adminLoginToast");
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            } else {
                errorMsg.classList.remove("d-none");
            }
        });
    }

    // Logout process
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            localStorage.removeItem("adminLoggedIn");
            showLoginView();

            const toastEl = document.getElementById("adminLogoutToast");
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    }

    // Sidebar Navigation logic
    if (adminNav) {
        adminNav.addEventListener("click", (e) => {
            const link = e.target.closest(".nav-link");
            if (!link || link.classList.contains("disabled")) return;

            e.preventDefault();
            const sectionId = link.getAttribute("data-section");

            adminNav.querySelectorAll(".nav-link").forEach(l => l.classList.remove("active"));
            link.classList.add("active");

            sections.forEach(section => {
                if (section.id === `section-${sectionId}`) {
                    section.classList.remove("d-none");
                } else {
                    section.classList.add("d-none");
                }
            });
        });
    }

    // --- Add Product Modal Logic ---
    if (categoryDropdownItems) {
        categoryDropdownItems.forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                const value = item.getAttribute("data-value");
                if (categoryText) categoryText.innerText = value;
                if (hiddenCategoryInput) hiddenCategoryInput.value = value;
            });
        });
    }

    // Make the entire upload wrapper clickable (not just the + label)
    if (uploadWrapper) {
        uploadWrapper.addEventListener("click", (e) => {
            // Don't double-fire if they clicked directly on the label/input
            if (e.target.closest("label") || e.target === imgUploadInput) return;
            imgUploadInput.click();
        });
    }

    // 2. Handle Multiple Photos Preview
    if (imgUploadInput) {
        imgUploadInput.addEventListener("change", (e) => {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const preview = document.createElement("div");
                    preview.className = "product-image-preview";
                    preview.innerHTML = `
                        <img src="${event.target.result}">
                        <button type="button" class="remove-img-btn">×</button>
                    `;

                    preview.querySelector(".remove-img-btn").addEventListener("click", () => {
                        preview.remove();
                    });

                    imgPreviewContainer.insertBefore(preview, uploadWrapper);
                };
                reader.readAsDataURL(file);
            });
            imgUploadInput.value = "";
        });
    }

    let editingRow = null;

    if (productList) {
        productList.addEventListener("click", (e) => {
            const deleteBtn = e.target.closest(".btn-delete-icon");
            if (deleteBtn) {
                const row = deleteBtn.closest("tr");
                if (row) row.classList.add("d-none");
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
                    const stockMatch = stockCellText.match(/\d+/);

                    const descAttr = row.getAttribute("data-desc") || "";
                    const imagesAttr = row.getAttribute("data-images");

                    document.getElementById("p-name").value = nameCell;
                    document.getElementById("p-price").value = priceCell;
                    document.getElementById("p-stock").value = stockMatch ? stockMatch[0] : "";
                    document.getElementById("p-desc").value = descAttr;
                    
                    if (categoryText) categoryText.innerText = categoryCell;
                    if (hiddenCategoryInput) hiddenCategoryInput.value = categoryCell;
                    
                    // Restore images in the preview container
                    const allPreviews = imgPreviewContainer?.querySelectorAll(".product-image-preview");
                    allPreviews?.forEach(p => p.remove());
                    
                    if (imagesAttr) {
                        try {
                            const imageUrls = JSON.parse(decodeURIComponent(imagesAttr));
                            imageUrls.forEach(src => {
                                const preview = document.createElement("div");
                                preview.className = "product-image-preview";
                                preview.innerHTML = `
                                    <img src="${src}">
                                    <button type="button" class="remove-img-btn">×</button>
                                `;
                                preview.querySelector(".remove-img-btn").addEventListener("click", () => preview.remove());
                                imgPreviewContainer.insertBefore(preview, uploadWrapper);
                            });
                        } catch (e) { console.error(e); }
                    } else {
                        // Dummy row fallback
                        const thumbImg = row.querySelector(".product-thumb-sm img");
                        if (thumbImg) {
                            const preview = document.createElement("div");
                            preview.className = "product-image-preview";
                            preview.innerHTML = `
                                <img src="${thumbImg.src}">
                                <button type="button" class="remove-img-btn">×</button>
                            `;
                            preview.querySelector(".remove-img-btn").addEventListener("click", () => preview.remove());
                            imgPreviewContainer.insertBefore(preview, uploadWrapper);
                        }
                    }

                    const modalLabel = document.getElementById("addProductModalLabel");
                    if (modalLabel) modalLabel.innerText = "Upraviť produkt";
                    
                    const modalEl = document.getElementById("addProductModal");
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }
        });
    }

    const addBtn = document.querySelector("[data-bs-target='#addProductModal']");
    if (addBtn) {
        addBtn.addEventListener("click", () => {
            editingRow = null;
            const modalLabel = document.getElementById("addProductModalLabel");
            if (modalLabel) modalLabel.innerText = "Pridať nový produkt";
            if (addProductForm) addProductForm.reset();
            if (categoryText) categoryText.innerText = "Vybrať kategóriu...";
            if (hiddenCategoryInput) hiddenCategoryInput.value = "";
            const allPreviews = imgPreviewContainer?.querySelectorAll(".product-image-preview");
            allPreviews?.forEach(p => p.remove());
        });
    }

    // 2. Form Submission with Dynamic List Update
    if (addProductForm) {
        addProductForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const name = document.getElementById("p-name").value;
            const category = document.getElementById("p-category").value;
            const price = document.getElementById("p-price").value;
            const stock = document.getElementById("p-stock").value;
            const desc = document.getElementById("p-desc").value;
            const previews = imgPreviewContainer.querySelectorAll(".product-image-preview");

            if (previews.length < 2) {
                const toastEl = document.getElementById("adminLoginToast");
                if (toastEl) {
                    const toastBody = toastEl.querySelector(".toast-body");
                    if (toastBody) toastBody.innerText = "Prosím pridajte aspoň 2 fotografie.";
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
                return;
            }

            const imageUrls = Array.from(previews).map(p => p.querySelector("img").src);

            if (editingRow) {
                // Update existing row
                const nameDiv = editingRow.querySelector("td:nth-child(2) div");
                if (nameDiv) {
                    let textNodeUpdated = false;
                    nameDiv.childNodes.forEach(node => {
                        if (node.nodeType === Node.TEXT_NODE && node.nodeValue.trim().length > 0) {
                            node.nodeValue = " " + name;
                            textNodeUpdated = true;
                        }
                    });
                    if (!textNodeUpdated) {
                        nameDiv.appendChild(document.createTextNode(" " + name));
                    }
                }
                editingRow.querySelector("td:nth-child(3)").innerText = category;
                editingRow.querySelector("td:nth-child(4)").innerText = parseFloat(price).toFixed(2) + " €";
                editingRow.querySelector("td:nth-child(5)").innerHTML = `<span class="badge bg-success">Na sklade (${parseInt(stock, 10)}ks)</span>`;
                
                editingRow.setAttribute("data-desc", desc);
                editingRow.setAttribute("data-images", encodeURIComponent(JSON.stringify(imageUrls)));

                if (previews.length > 0) {
                    const thumbImg = editingRow.querySelector(".product-thumb-sm img");
                    if (thumbImg) {
                        thumbImg.src = previews[0].querySelector("img").src;
                    } else if (editingRow.querySelector(".product-thumb-sm")) {
                        editingRow.querySelector(".product-thumb-sm").innerHTML = `<img src="${previews[0].querySelector("img").src}" style="width:100%; height:100%; object-fit:cover;">`;
                        editingRow.querySelector(".product-thumb-sm").classList.add("overflow-hidden");
                    }
                }
            } else {
                // Add new row
                const newRow = document.createElement("tr");
                newRow.setAttribute("data-desc", desc);
                newRow.setAttribute("data-images", encodeURIComponent(JSON.stringify(imageUrls)));
                newRow.innerHTML = `
                    <td class="ps-4">#${Math.floor(1000 + Math.random() * 9000)}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="product-thumb-sm bg-prussian-blue rounded overflow-hidden">
                               <img src="${previews[0].querySelector("img").src}" style="width:100%; height:100%; object-fit:cover;">
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
                productList.prepend(newRow);
            }

            const modalEl = document.getElementById("addProductModal");
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            addProductForm.reset();
            const allPreviews = imgPreviewContainer.querySelectorAll(".product-image-preview");
            allPreviews.forEach(p => p.remove());

            if (categoryText) categoryText.innerText = "Vybrať kategóriu...";
            if (hiddenCategoryInput) hiddenCategoryInput.value = "";

            const toastEl = document.getElementById("adminLoginToast");
            if (toastEl) {
                const toastBody = toastEl.querySelector(".toast-body");
                if (toastBody) toastBody.innerText = editingRow ? "Produkt bol upravený!" : "Produkt bol úspešne pridaný!";
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
            editingRow = null;
        });
    }

    function showDashboard() {
        if (loginView && dashboardView) {
            loginView.classList.add("d-none");
            loginView.classList.remove("d-flex");
            dashboardView.classList.remove("d-none");
            errorMsg?.classList.add("d-none");
            loginForm?.reset();
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
