<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="d-flex flex-column align-items-center w-100">
            <div class="modal-content auth-modal p-4 w-100 shadow-lg">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h4 class="modal-title m-0">Prihlásenie</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <p class="small mb-4 auth-modal-subtitle">Ak ešte nemáte účet, kliknite <a href="#"
                        data-bs-toggle="modal" data-bs-target="#registerModal"
                        class="text-decoration-none auth-modal-link">TU</a></p>

                <form id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1">E-mail</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4 text-start">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small mb-1">Heslo</label>
                            <a href="#" class="small text-decoration-none forgot-password-link">Zabudnuté heslo?</a>
                        </div>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-4 py-2">Prihlásiť sa</button>
                </form>

                <div class="text-center">
                    <p class="small mb-3 auth-modal-footer-text">Alebo sa prihlásiť pomocou</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/google.png') }}"
                                alt="Google" class="social-icon-img"></a>
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/facebook.png') }}"
                                alt="Facebook" class="social-icon-img icon-white"></a>
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/apple.png') }}"
                                alt="Apple" class="social-icon-img icon-white"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- REGISTER MODAL -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="d-flex flex-column align-items-center w-100">
            <div class="modal-content auth-modal p-4 w-100 shadow-lg">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h4 class="modal-title m-0">Registrácia</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="register-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1">Meno</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1">Priezvisko</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label small mb-1">E-mail</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4 text-start">
                        <label class="form-label small mb-1">Heslo</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-4 py-2">Registrovať sa</button>
                </form>

                <div class="text-center">
                    <p class="small mb-3 auth-modal-footer-text">Alebo sa registrovať pomocou</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/google.png') }}"
                                alt="Google" class="social-icon-img"></a>
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/facebook.png') }}"
                                alt="Facebook" class="social-icon-img icon-white"></a>
                        <a href="#" class="social-login-btn"><img src="{{ asset('assets/apple.png') }}"
                                alt="Apple" class="social-icon-img icon-white"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LOGOUT TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="logoutToast" class="toast align-items-center text-white bg-dark border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Boli ste úspešne odhlásený.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- CART OVERLAY -->
<div id="cart-overlay" class="cart-overlay"></div>

<!-- CART DRAWER -->
<div id="cart-drawer" class="cart-drawer">
    <div class="cart-drawer__header">
        <span class="cart-drawer__title">Košík</span>
        <button class="cart-drawer__close" id="cart-close" aria-label="Zavrieť">
            <img src="{{ asset('assets/close.png') }}" class="icon-sm icon-white">
        </button>
    </div>

    <div class="cart-drawer__body" id="cart-body">
    </div>

    <div class="cart-drawer__footer">
        <div class="cart-total">
            <span>Spolu</span>
            <span id="cart-total-price">0,00 €</span>
        </div>
        <a href="{{ route('cart') }}" class="btn-checkout">Prejsť na objednávku</a>
    </div>
</div>

<!-- APP MODAL -->
<div class="modal fade" id="appModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-modal shadow-lg">
            <div class="modal-body app-modal-body text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="{{ asset('assets/logo.png') }}" class="app-icon-large" alt="App Icon">
                <h2 class="app-modal-title">Stiahnite si našu aplikáciu</h2>
                <p class="app-modal-text">Nakupujte ešte rýchlejšie a pohodlnejšie s mobilnou aplikáciou RackRush.
                    Majte svoje nákupné zoznamy a obľúbené produkty vždy po ruke (aj bez pripojenia k sieti).</p>
                <div class="app-store-container">
                    <a href="https://play.google.com/store" target="_blank">
                        <img src="{{ asset('assets/google-play.png') }}" alt="Google Play" class="app-store-badge">
                    </a>
                    <a href="https://www.apple.com/app-store/" target="_blank">
                        <img src="{{ asset('assets/app-store.png') }}" alt="App Store" class="app-store-badge">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
