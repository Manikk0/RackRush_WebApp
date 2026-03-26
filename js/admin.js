document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("admin-login-form");
    const loginView = document.getElementById("admin-login-view");
    const dashboardView = document.getElementById("admin-dashboard-view");
    const logoutBtn = document.getElementById("admin-logout-btn");
    const errorMsg = document.getElementById("admin-login-error");

    if (localStorage.getItem("adminLoggedIn") === "true") {
        showDashboard();
    }

    if(loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            
            const email = document.getElementById("admin-email").value.trim();
            const password = document.getElementById("admin-password").value.trim();

            if (email && password) {
                localStorage.setItem("adminLoggedIn", "true");
                showDashboard();
                
                const toastEl = document.getElementById("adminLoginToast");
                if(toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            } else {
                errorMsg.classList.remove("d-none");
            }
        });
    }

    // Logout process
    if(logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            localStorage.removeItem("adminLoggedIn");
            showLoginView();
            
            const toastEl = document.getElementById("adminLogoutToast");
            if(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    }

    function showDashboard() {
        if(loginView && dashboardView) {
            loginView.classList.add("d-none");
            loginView.classList.remove("d-flex");
            dashboardView.classList.remove("d-none");
            errorMsg?.classList.add("d-none");
            loginForm?.reset();
        }
    }

    function showLoginView() {
        if(loginView && dashboardView) {
            loginView.classList.add("d-flex");
            loginView.classList.remove("d-none");
            dashboardView.classList.add("d-none");
        }
    }
});
