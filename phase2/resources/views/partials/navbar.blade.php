@php
    $headerSearchQuery = request('q', '');
@endphp

<!-- HEADER -->
<header id="main-header" class="fixed-top shadow-sm">
    <div class="container-fluid px-4">

        <!-- DESKTOP NAVIGATION -->
        <div id="desktop-nav" class="d-none d-md-block">
            <div id="top-row" class="row align-items-center py-2">
                <div class="col-3">
                    <a href="{{ route('index') }}" class="d-inline-block">
                        <img src="{{ asset('assets/logo.png') }}" alt="RackRush" class="logo-placeholder">
                    </a>
                </div>

                <div class="col-6">
                    <form action="{{ route('search') }}" method="GET" class="header-search-form" role="search">
                        <div class="input-group search-container">
                            <input type="search" name="q" value="{{ $headerSearchQuery }}" class="form-control"
                                placeholder="Hľadať produkty..." maxlength="120" autocomplete="off"
                                aria-label="Hľadať produkty">
                            <button type="submit" class="btn btn-search-submit" aria-label="Hľadať">
                                <img src="{{ asset('assets/search.png') }}" class="icon-sm" alt="">
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-3 d-flex justify-content-end gap-4 align-items-center">
                    <!-- NOTIFICATIONS -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle position-relative" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside">
                            <img src="{{ asset('assets/bell.png') }}" alt="Upozornenia" class="icon-md icon-white">
                            <span class="notification-badge">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown mt-3 shadow-lg">
                            <div class="notifications-body">
                                <a href="#" class="notification-item unread">
                                    <span class="notification-title">Vybavenie objednávky</span>
                                    <span class="notification-text">Vaša objednávka č. 123456 bola úspešne
                                        odoslaná.</span>
                                    <span class="notification-time">12:40</span>
                                </a>
                                <a href="#" class="notification-item">
                                    <span class="notification-title">Zľavový kód</span>
                                    <span class="notification-text">Pridali sme vám 10% zľavu na ďalší nákup!</span>
                                    <span class="notification-time">10:15</span>
                                </a>
                                <a href="#" class="notification-item">
                                    <span class="notification-title">Zmena v sortimente</span>
                                    <span class="notification-text">Nový tovar v kategórii Ovocie a zelenina.</span>
                                    <span class="notification-time">Včera</span>
                                </a>
                                <a href="#" class="notification-item">
                                    <span class="notification-title">Vernostný program</span>
                                    <span class="notification-text">Získali ste 50 nových bodov za váš posledný
                                        nákup.</span>
                                    <span class="notification-time">Predvčerom</span>
                                </a>
                                <a href="#" class="notification-item">
                                    <span class="notification-title">Dostupnosť tovaru</span>
                                    <span class="notification-text">Hrušky, ktoré ste sledovali, sú opäť na
                                        sklade.</span>
                                    <span class="notification-time">15. Mar</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- CART TRIGGER -->
                    <a href="#" id="cart-trigger" class="cart-trigger-btn position-relative">
                        <img src="{{ asset('assets/shopping-cart.png') }}" alt="Košík" class="icon-md icon-white">
                        <span class="cart-badge d-none" id="cart-badge">0</span>
                    </a>

                    <!-- USER DROPDOWN -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <img src="{{ asset('assets/user.png') }}" alt="Profil" class="icon-md icon-white">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end user-dropdown-menu mt-3 shadow-lg" id="userMenu">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <a class="dropdown-item @guest link-greyed @endguest" href="#"><img
                                            src="{{ asset('assets/task_complete.png') }}" class="icon-sm icon-theme">
                                        Objednávky</a>
                                    <a class="dropdown-item @guest link-greyed @endguest" href="#"><img
                                            src="{{ asset('assets/user.png') }}" class="icon-sm icon-theme"> Môj
                                        účet</a>
                                    <a class="dropdown-item" href="#"><img
                                            src="{{ asset('assets/question_mark.png') }}" class="icon-sm icon-theme">
                                        Ako nakúpiť</a>
                                    <a class="dropdown-item" href="#"><img
                                            src="{{ asset('assets/speech-bubble.png') }}" class="icon-sm icon-theme">
                                        Potrebujem poradiť</a>
                                </div>

                                <div class="v-divider"></div>

                                <div class="user-dropdown-info">
                                    <!-- LOGGED IN STATE -->
                                    @auth
                                    <div id="dropdown-logged-in">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <img src="{{ asset('assets/user_placeholder_profile.png') }}"
                                                class="icon-md icon-theme">
                                            <div class="user-info-text">
                                                <strong class="d-block text-nowrap user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
                                                <span class="text-muted small user-email">{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endauth

                                    <!-- LOGGED OUT STATE -->
                                    @guest
                                    <div id="dropdown-logged-out">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <img src="{{ asset('assets/user.png') }}"
                                                class="icon-md logged-out-user-icon">
                                            <strong class="logged-out-text">Prihláste sa a získate<br>množstvo
                                                výhod</strong>
                                        </div>
                                        <div class="d-flex flex-column gap-2 mb-4">
                                            <a href="#" class="auth-link" data-bs-toggle="modal"
                                                data-bs-target="#loginModal">Prihlásiť sa</a>
                                            <a href="#" class="auth-link" data-bs-toggle="modal"
                                                data-bs-target="#registerModal">Zaregistrovať sa</a>
                                        </div>
                                    </div>
                                    @endguest

                                    <!-- LANGUAGE SELECTOR -->
                                    <div>
                                        <p class="small fw-bold mb-2 lang-section-title">Zmeniť jazyk</p>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="#" class="lang-link"><img src="https://flagcdn.com/w20/gb.png"
                                                    width="20"> EN</a>
                                            <a href="#" class="lang-link"><img src="https://flagcdn.com/w20/cz.png"
                                                    width="20"> CZ</a>
                                            <a href="#" class="lang-link"><img src="https://flagcdn.com/w20/de.png"
                                                    width="20"> DE</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 dropdown-divider-dim">

                            <div class="d-flex justify-content-between align-items-center w-100">
                                @auth
                                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-inline">
                                    @csrf
                                    <button type="submit" class="logout-pill border-0" id="logout-btn">
                                        <img src="{{ asset('assets/logout.png') }}" class="icon-sm icon-theme">
                                        <strong>Odhlásiť sa</strong>
                                    </button>
                                </form>
                                @endauth
                                <div class="promo-bubble ms-auto">
                                    Pri použití našej Aplikácie získate 25% zľavu k prvému nákupu
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUB NAVIGATION -->
            <div id="bottom-row" class="row align-items-center py-2">
                <div class="col-auto d-flex gap-2">
                    <div>
                        <a href="{{ route('categories') }}" class="btn btn-teal">Kategórie</a>
                    </div>
                    <a href="#" class="btn btn-outline-custom">Obľúbené</a>
                    <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#appModal">Naša
                        aplikácia</button>
                </div>

                <div class="col d-none" id="sticky-search-container">
                    <form action="{{ route('search') }}" method="GET" class="header-search-form ms-3" role="search">
                        <div class="input-group search-container">
                            <input type="search" name="q" value="{{ $headerSearchQuery }}" class="form-control"
                                placeholder="Hľadať produkty..." maxlength="120" autocomplete="off" aria-label="Hľadať">
                            <button type="submit" class="btn btn-search-submit" aria-label="Hľadať">
                                <img src="{{ asset('assets/search.png') }}" class="icon-sm" alt="">
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-auto ms-auto d-flex align-items-center">
                    <a href="#" id="btn-lists" class="btn btn-outline-custom">Nákupné zoznamy</a>
                    <div id="sticky-cart" class="d-none ms-3">
                        <a href="{{ route('cart') }}" class="cart-trigger-btn position-relative">
                            <img src="{{ asset('assets/shopping-cart.png') }}" alt="Košík" class="icon-md icon-white">
                            <span class="cart-badge d-none">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE NAVIGATION -->
        <div id="mobile-nav" class="row align-items-center py-2 d-flex d-md-none">
            <div class="col-auto">
                <button class="btn p-0 border-0" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <img src="{{ asset('assets/burger-menu.png') }}" alt="Menu" class="icon-md icon-white">
                </button>
            </div>
            <div class="col px-1 mobile-search-col">
                <form action="{{ route('search') }}" method="GET" class="header-search-form" role="search">
                    <div class="input-group search-container">
                        <input type="search" name="q" value="{{ $headerSearchQuery }}" class="form-control"
                            placeholder="Hľadať produkty..." maxlength="120" autocomplete="off" aria-label="Hľadať">
                        <button type="submit" class="btn btn-search-submit" aria-label="Hľadať">
                            <img src="{{ asset('assets/search.png') }}" class="icon-sm" alt="">
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-auto">
                <a href="{{ route('cart') }}" class="cart-trigger-btn position-relative">
                    <img src="{{ asset('assets/shopping-cart.png') }}" alt="Košík" class="icon-md icon-white">
                    <span class="cart-badge d-none">0</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- MOBILE OFFCANVAS -->
<div class="offcanvas offcanvas-start offcanvas-mobile" tabindex="-1" id="mobileMenu"
    aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">
            <a href="{{ route('index') }}">
                <img src="{{ asset('assets/logo.png') }}" alt="RackRush" class="mobile-logo">
            </a>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- USER SECTION -->
        <div class="mobile-menu-section">
            <!-- LOGGED IN USER -->
            @auth
            <div id="mobile-logged-in">
                <div class="mobile-user-card d-flex align-items-center gap-3">
                    <img src="{{ asset('assets/user_placeholder_profile.png') }}" class="icon-md icon-theme">
                    <div>
                        <div class="mobile-user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/user.png') }}" class="icon-sm"> Môj
                    účet</a>
                <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/task_complete.png') }}"
                        class="icon-sm">
                    Objednávky</a>
            </div>
            @endauth

            <!-- LOGGED OUT USER -->
            @guest
            <div id="mobile-logged-out">
                <div class="mobile-auth-btns">
                    <button class="btn btn-teal w-100 mb-2" data-bs-toggle="modal"
                        data-bs-target="#loginModal">Prihlásiť sa</button>
                    <button class="btn btn-outline-custom w-100" data-bs-toggle="modal"
                        data-bs-target="#registerModal">Registrácia</button>
                </div>
            </div>
            @endguest
        </div>

        <!-- MAIN MENU -->
        <div class="mobile-menu-section">
            <p class="mobile-menu-title">Menu</p>
            <a href="{{ route('categories') }}" class="mobile-nav-link"><img src="{{ asset('assets/category.png') }}"
                    class="icon-sm">
                Kategórie</a>
            <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/heart.png') }}" class="icon-sm">
                Obľúbené</a>
            <a href="#" class="mobile-nav-link" data-bs-toggle="modal" data-bs-target="#appModal"><img
                    src="{{ asset('assets/tag.png') }}" class="icon-sm"> Naša aplikácia</a>
            <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/task_complete.png') }}"
                    class="icon-sm"> Nákupné
                zoznamy</a>
        </div>

        <!-- MOBILE NOTIFICATIONS -->
        <div class="mobile-menu-section">
            <p class="mobile-menu-title d-flex justify-content-between align-items-center">
                Upozornenia
                <span class="badge rounded-pill bg-danger badge-micro">5</span>
            </p>
            <div class="mobile-notifications-list mt-3">
                <a href="#" class="mobile-notification-item unread">
                    <span class="mobile-notification-title">Vybavenie objednávky</span>
                    <span class="mobile-notification-text">Vaša objednávka č. 123456 bola úspešne odoslaná.</span>
                    <span class="mobile-notification-time">12:40</span>
                </a>
                <a href="#" class="mobile-notification-item">
                    <span class="mobile-notification-title">Zľavový kód</span>
                    <span class="mobile-notification-text">Pridali sme vám 10% zľavu na ďalší nákup!</span>
                    <span class="mobile-notification-time">10:15</span>
                </a>
                <a href="#" class="mobile-notification-item">
                    <span class="mobile-notification-title">Zmena v sortimente</span>
                    <span class="mobile-notification-text">Nový tovar v kategórii Ovocie a zelenina.</span>
                    <span class="notification-time">Včera</span>
                </a>
                <a href="#" class="mobile-notification-item">
                    <span class="mobile-notification-title">Vernostný program</span>
                    <span class="mobile-notification-text">Získali ste 50 nových bodov za váš posledný nákup.</span>
                    <span class="mobile-notification-time">Predvčerom</span>
                </a>
                <a href="#" class="mobile-notification-item">
                    <span class="mobile-notification-title">Dostupnosť tovaru</span>
                    <span class="mobile-notification-text">Hrušky, ktoré ste sledovali, sú opäť na sklade.</span>
                    <span class="mobile-notification-time">15. Mar</span>
                </a>
            </div>
        </div>

        <!-- OTHERS SECTION -->
        <div class="mobile-menu-section">
            <p class="mobile-menu-title">Ostatné</p>
            <div class="mobile-lang-selector mb-3 px-1">
                <a href="#" class="mobile-lang-link active">SK</a>
                <a href="#" class="mobile-lang-link">EN</a>
                <a href="#" class="mobile-lang-link">CZ</a>
                <a href="#" class="mobile-lang-link">DE</a>
            </div>
            <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/question_mark.png') }}"
                    class="icon-sm"> Ako nakúpiť</a>
            <a href="#" class="mobile-nav-link"><img src="{{ asset('assets/speech-bubble.png') }}"
                    class="icon-sm"> Potrebujem
                poradiť</a>
        </div>

        <!-- MOBILE LOGOUT -->
        @auth
        <div class="mobile-menu-section" id="mobile-logout-section">
            <form action="{{ route('logout') }}" method="POST" id="logout-form-mobile">
                @csrf
                <button type="submit" class="mobile-nav-link text-danger logout-btn-mobile border-0 bg-transparent p-0 w-100 text-start">
                    <img src="{{ asset('assets/logout.png') }}" class="icon-sm logout-icon-mobile">
                    <strong>Odhlásiť sa</strong>
                </button>
            </form>
        </div>
        @endauth
    </div>
</div>
