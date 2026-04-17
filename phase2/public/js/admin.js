// Admin page UI interactions with backend integration for product management
let editingRow = null;
let uploadedImages = [];

// Initialize admin page listeners and load products
document.addEventListener("DOMContentLoaded", function () {
    const adminNav = document.getElementById("admin-nav");
    const sections = document.querySelectorAll(".admin-section");
    const addProductForm = document.getElementById("add-product-form");
    const productList = document.getElementById("admin-product-list");
    const imgUploadInput = document.getElementById("p-img-upload");
    const imgPreviewContainer = document.getElementById("product-images-container");
    const searchInput = document.getElementById("admin-product-search");
    const searchBtn = document.getElementById("admin-search-btn");

    let uploadWrapper = null;
    if (imgPreviewContainer) {
        uploadWrapper = imgPreviewContainer.querySelector(".image-upload-wrapper");
    }

    const categoryText = document.getElementById("selected-category-text");
    const hiddenCategoryInput = document.getElementById("p-category");
    const categoryDropdownItems = document.querySelectorAll(".category-select-item");

    // Load products when products section is shown
    loadProducts();

    // Sidebar tab navigation
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
                    // Load products when switching to products section
                    if (sectionId === "products") {
                        loadProducts();
                    }
                } else {
                    section.classList.add("d-none");
                }
            }
        });
    }

    // Category dropdown selection
    for (let i = 0; i < categoryDropdownItems.length; i++) {
        const item = categoryDropdownItems[i];
        item.addEventListener("click", function (e) {
            e.preventDefault();
            const value = this.getAttribute("data-value");
            if (categoryText) { categoryText.innerText = value; }
            if (hiddenCategoryInput) { hiddenCategoryInput.value = value; }
        });
    }

    // Image upload handling
    if (imgUploadInput) {
        imgUploadInput.addEventListener("change", function (e) {
            const files = Array.from(e.target.files);
            uploadedImages = uploadedImages.concat(files);
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith("image/")) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        createImagePreview(event.target.result, file);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    // Search functionality
    if (searchBtn) {
        searchBtn.addEventListener("click", function() {
            loadProducts();
        });
    }

    if (searchInput) {
        searchInput.addEventListener("keyup", function(e) {
            if (e.key === 'Enter') {
                loadProducts();
            }
        });
    }

    // Add product form submission
    if (addProductForm) {
        addProductForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleProductSubmit();
        });
    }

    // Delete confirmation modal setup
    setupDeleteModal();
    
    // Setup input limits for 3-digit restriction
    setupInputLimits();
});

// Setup input limits to prevent typing beyond 3-digit restriction
function setupInputLimits() {
    const priceInput = document.getElementById('p-price');
    const stockInput = document.getElementById('p-stock');
    
    // Price input limit (max 999.99)
    if (priceInput) {
        priceInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove any characters beyond 6 characters (999.99)
            if (value.length > 6) {
                e.target.value = value.substring(0, 6);
            }
            
            // Ensure numeric format with max 2 decimal places
            const numericValue = parseFloat(e.target.value);
            if (!isNaN(numericValue) && numericValue > 999.99) {
                e.target.value = '999.99';
            }
        });
    }
    
    // Stock input limit (max 999)
    if (stockInput) {
        stockInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove any characters beyond 3 characters
            if (value.length > 3) {
                e.target.value = value.substring(0, 3);
            }
            
            // Ensure numeric value doesn't exceed 999
            const numericValue = parseInt(e.target.value);
            if (!isNaN(numericValue) && numericValue > 999) {
                e.target.value = '999';
            }
        });
    }
}

// Load products from backend and display in table
function loadProducts() {
    const searchInput = document.getElementById("admin-product-search");
    const searchValue = searchInput ? searchInput.value : '';
    
    fetch(`/api/admin/products?search=${encodeURIComponent(searchValue)}`)
        .then(response => response.json())
        .then(data => {
            displayProducts(data.products);
        })
        .catch(error => {
            console.error('Error loading products:', error);
            showErrorToast('Nepodarilo sa načítať produkty');
        });
}

// Display products in admin table
function displayProducts(products) {
    const productList = document.getElementById("admin-product-list");
    if (!productList) return;

    productList.innerHTML = '';

    if (products.length === 0) {
        productList.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-white-50">
                    Žiadne produkty neboli nájdené
                </td>
            </tr>
        `;
        return;
    }

    for (let i = 0; i < products.length; i++) {
        const product = products[i];
        const row = createProductRow(product);
        productList.appendChild(row);
    }
}

// Create product row for table
function createProductRow(product) {
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td class="ps-4">#${product.id}</td>
        <td>${product.name}</td>
        <td>${product.kategoria ? product.kategoria.name : 'Neznáma'}</td>
        <td>${parseFloat(product.price).toFixed(2)} €</td>
        <td><span class="badge bg-success">Na sklade (${parseInt(product.quantity, 10)}ks)</span></td>
        <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-edit-icon" title="Upraviť" onclick="editProduct(${product.id})">
                    <img src="/assets/pencil.png" class="icon-xs icon-white">
                </button>
                <button class="btn btn-delete-icon" title="Vymazať" onclick="confirmDelete(${product.id}, '${product.name.replace(/'/g, "\\'")}')">
                    <img src="/assets/trash.png" class="icon-xs icon-white">
                </button>
            </div>
        </td>
    `;
    
    return row;
}

// Create image preview for uploaded files
function createImagePreview(imageSrc, file) {
    const imgPreviewContainer = document.getElementById("product-images-container");
    if (!imgPreviewContainer) return;

    const previewWrapper = document.createElement("div");
    previewWrapper.className = "product-image-preview p-2 border border-dusk-blue rounded position-relative";

    const img = document.createElement("img");
    img.src = imageSrc;
    img.className = "img-fluid";
    img.style.height = "80px";
    img.style.width = "80px";
    img.style.objectFit = "cover";

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "btn btn-sm btn-danger position-absolute top-0 end-0 m-1";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = function () {
        previewWrapper.remove();
        const index = uploadedImages.indexOf(file);
        if (index > -1) {
            uploadedImages.splice(index, 1);
        }
    };

    previewWrapper.appendChild(img);
    previewWrapper.appendChild(removeBtn);

    // Insert before upload button
    const uploadBtn = imgPreviewContainer.querySelector(".image-upload-wrapper");
    if (uploadBtn) {
        imgPreviewContainer.insertBefore(previewWrapper, uploadBtn);
    }
}

// Handle product form submission
function handleProductSubmit() {
    // Get form values
    const name = document.getElementById('p-name').value;
    const description = document.getElementById('p-desc').value;
    const category = document.getElementById('p-category').value;
    const price = document.getElementById('p-price').value;
    const stock = document.getElementById('p-stock').value;

    // Validate required fields with specific messages
    if (!name) {
        showErrorToast('Názov produktu je povinný');
        return;
    }
    
    if (!description) {
        showErrorToast('Popis produktu je povinný');
        return;
    }
    
    if (!category || category === 'Vybra kategóriu...' || category.trim() === '') {
        showErrorToast('Prosím vyberte kategóriu');
        return;
    }
    
    if (!price || parseFloat(price) <= 0) {
        showErrorToast('Cena musí by kladné íslo');
        return;
    }
    
    if (!stock || parseInt(stock) < 0) {
        showErrorToast('Mnozstvo na sklade musí by nezáporné íslo');
        return;
    }
    
    
    if (uploadedImages.length < 2) {
        showErrorToast('Minimálne 2 obrázky sú povinné');
        return;
    }

    // Create FormData
    const formData = new FormData();
    
    // Append form data
    formData.append('name', name);
    formData.append('description', description);
    formData.append('category', category);
    formData.append('price', price);
    formData.append('stock', stock);

    // Append images
    for (let i = 0; i < uploadedImages.length; i++) {
        formData.append('images[]', uploadedImages[i]);
    }

    // Submit to backend
    fetch('/api/admin/products', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        
        // Try to parse as JSON
        return response.text().then(text => {
            console.log('Raw response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                throw new Error(`Invalid JSON response: ${text.substring(0, 100)}...`);
            }
        });
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showSuccessToast(data.message);
            resetProductForm();
            loadProducts(); // Reload products list
            
            // Close modal
            const modalEl = document.getElementById("addProductModal");
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) { modal.hide(); }
            }
        } else {
            // Handle validation errors
            if (data.errors) {
                let errorMessage = 'Chyby validácie:\n';
                for (let field in data.errors) {
                    errorMessage += data.errors[field][0] + '\n';
                }
                showErrorToast(errorMessage);
            } else {
                showErrorToast(data.message || 'Neznáma chyba');
            }
        }
    })
    .catch(error => {
        console.error('Error adding product:', error);
        showErrorToast('Nastala chyba pri pridávaní produktu: ' + error.message);
    });
}

// Reset product form
function resetProductForm() {
    const addProductForm = document.getElementById("add-product-form");
    if (addProductForm) {
        addProductForm.reset();
    }
    
    // Reset category selection
    const categoryText = document.getElementById("selected-category-text");
    const hiddenCategoryInput = document.getElementById("p-category");
    if (categoryText) { categoryText.innerText = "Vybrať kategóriu..."; }
    if (hiddenCategoryInput) { hiddenCategoryInput.value = ""; }
    
    // Clear image previews
    const imgPreviewContainer = document.getElementById("product-images-container");
    if (imgPreviewContainer) {
        const previews = imgPreviewContainer.querySelectorAll(".product-image-preview");
        for (let i = 0; i < previews.length; i++) {
            previews[i].remove();
        }
    }
    
    // Reset uploaded images array
    uploadedImages = [];
}

// Setup delete confirmation modal
function setupDeleteModal() {
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const productId = document.getElementById('delete-product-id').value;
            deleteProduct(productId);
        });
    }
}

// Confirm product deletion
function confirmDelete(productId, productName) {
    const modalEl = document.getElementById('deleteProductModal');
    const productIdEl = document.getElementById('delete-product-id');
    
    if (productIdEl) productIdEl.value = productId;
    
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

// Delete product from database
function deleteProduct(productId) {
    fetch(`/api/admin/products/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message);
            loadProducts(); // Reload products list
            
            // Close modal
            const modalEl = document.getElementById('deleteProductModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) { modal.hide(); }
            }
        } else {
            showErrorToast(data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting product:', error);
        showErrorToast('Nastala chyba pri mazaní produktu');
    });
}

// Edit product (placeholder for future implementation)
function editProduct(productId) {
    showSuccessToast('Funkcia úpravy produktu bude implementovaná neskôr');
}

// Toast notification functions
function showSuccessToast(message) {
    showToast(message, 'success');
}

function showErrorToast(message) {
    showToast(message, 'error');
}

function showToast(message, type) {
    // Create toast element if it doesn't exist
    let toastEl = document.getElementById('adminNotificationToast');
    if (!toastEl) {
        const toastContainer = document.querySelector('.toast-container');
        if (toastContainer) {
            toastEl = document.createElement('div');
            toastEl.id = 'adminNotificationToast';
            toastEl.className = 'toast align-items-center border-0';
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body"></div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastEl);
        }
    }
    
    if (toastEl) {
        const toastBody = toastEl.querySelector('.toast-body');
        if (toastBody) {
            toastBody.textContent = message;
        }
        
        // Set toast color based on type
        toastEl.className = 'toast align-items-center border-0';
        if (type === 'success') {
            toastEl.classList.add('toast-success-custom');
        } else {
            toastEl.classList.add('bg-danger', 'text-white');
        }
        
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}
