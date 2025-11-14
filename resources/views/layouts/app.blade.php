<!doctype html>
<html lang="en">
<head>
    <title>@yield('title') | JORONG BARUTAMA GRESTON</title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Sistem Surat Desa Jorong Barutama Greston" />
    <meta name="keywords" content="Laravel admin template, surat masuk, surat keluar, desa" />
    <meta name="author" content="Jorong Barutama Greston" />

    <!-- [Favicon] -->
    <link rel="icon" href="{{ asset('gradient/assets/images/favicon.svg') }}" type="image/x-icon" />

    <!-- [Google Font : Poppins] -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- [Icon Fonts] -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/material.css') }}" />

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/js/plugins/jsvectormap.min.css') }}" />
</head>

<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light"
      data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ url('/') }}" class="b-brand text-primary">
                    <img src="{{ asset('gradient/assets/images/logojbg2.png') }}" alt="Logo JBG" class="logo-lg" style="height: 50px; width: auto;" />
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="pc-item">
                        <a href="{{ url('/') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ url('/surat-masuk') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-mail"></i></span>
                            <span class="pc-mtext">Surat Masuk</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ url('/surat-keluar') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-send"></i></span>
                            <span class="pc-mtext">Surat Keluar</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ url('/agenda') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-calendar"></i></span>
                            <span class="pc-mtext">Agenda</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Pages</label>
                        <i class="ph ph-devices"></i>
                    </li>
                    <li class="pc-item">
                        <a href="/gradient/pages/login-v1.html" target="_blank" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-lock"></i></span>
                            <span class="pc-mtext">Login</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="/gradient/pages/register-v1.html" target="_blank" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-user-circle-plus"></i></span>
                            <span class="pc-mtext">Register</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="m-header">
                <a href="{{ url('/') }}" class="b-brand text-primary">
                    <img src="{{ asset('gradient/assets/images/logojbg2.png') }}" alt="Logo JBG" class="logo-lg" style="height: 50px; width: auto;" />
                </a>
            </div>
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <i class="ph ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <input type="search" class="form-control border-0 shadow-none" placeholder="Search..." />
                                    <button class="btn btn-light-secondary btn-search">Search</button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <img src="{{ asset('gradient/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar" />
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-body">
                                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                    <ul class="list-group list-group-flush w-100">
                                        <li class="list-group-item">
                                            <a href="#" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph ph-user-circle"></i>
                                                    <span>Profile</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <a href="#" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph ph-power"></i>
                                                    <span>Logout</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- [ Footer ] start -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm-6 my-1">
                    <p class="m-0">© 2025 Jorong Barutama Greston. All rights reserved.</p>
                </div>
                <div class="col-sm-6 ms-auto my-1 text-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="{{ url('/') }}">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- [ Footer ] end -->

    <!-- [Page Specific JS] -->
    <script src="{{ asset('gradient/assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/world.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/world-merc.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/pages/dashboard-sales.js') }}"></script>

    <!-- [Required JS] -->
    <script src="{{ asset('gradient/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/script.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/theme.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
        layout_sidebar_change('light');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        header_change('header-1');
    </script>
</body>
</html>
