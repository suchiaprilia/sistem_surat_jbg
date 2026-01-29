<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                    <img src="{{ asset('gradient/assets/images/logojbg2.png') }}" alt="Logo JBG" class="logo-lg" style="height: 50px; width: auto;" />
                </a>
            </div>

            <div class="navbar-content">
                <ul class="pc-navbar">

                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-gauge"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('surat-masuk.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-mail"></i></span>
                            <span class="pc-mtext">Surat Masuk</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('disposisi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-inbox"></i></span>
                            <span class="pc-mtext">Disposisi Saya</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('surat-keluar.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-send"></i></span>
                            <span class="pc-mtext">Surat Keluar</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('rekap-surat') }}" class="pc-link {{ request()->is('rekap-surat') ? 'active' : '' }}">
                            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                            <span class="pc-mtext">Rekap Surat</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ url('/agenda') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-calendar"></i></span>
                            <span class="pc-mtext">Agenda</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Master</label>
                        <i class="ph ph-folder"></i>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('divisi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-building"></i></span>
                            <span class="pc-mtext">Divisi</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('jabatan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-id"></i></span>
                            <span class="pc-mtext">Jabatan</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('karyawan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Karyawan</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('jenis-surat.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="fas fa-tags"></i></span>
                            <span class="pc-mtext">Jenis Surat</span>
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
                <a href="{{ route('dashboard') }}" class="b-brand text-primary">
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
                            <form class="px-3" method="GET" action="{{ route('search') }}">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <input type="search" name="q" class="form-control border-0 shadow-none" placeholder="Cari surat..." />
                                    <button type="submit" class="btn btn-light-secondary btn-search">Cari</button>
                                </div>
                            </form>
                        </div>
                    </li>

                </ul>
            </div>

            {{-- ✅ Blok PHP notifikasi ditempatkan tepat sebelum section ms-auto --}}
            @php
                use App\Http\Controllers\NotifikasiController;
                $notifDisposisi = NotifikasiController::disposisiBaru();
            @endphp

            <div class="ms-auto">
                <ul class="list-unstyled">
                    {{-- ✅ ICON LONCENG - SATU SAJA --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-3"
                           data-bs-toggle="dropdown" href="#">
                            <i class="ph ph-bell"></i>

                            <span id="notif-badge"
                                  class="badge bg-danger rounded-circle"
                                  style="position:absolute; top:0; right:0; display:none;
                                         font-size:0.75rem; min-width:18px; height:18px;
                                         align-items:center; justify-content:center;">
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h6 class="m-0">Notifikasi</h6>
                            </div>

                            <div class="dropdown-body" id="notif-list">
                                <span class="dropdown-item text-muted">Loading...</span>
                            </div>
                        </div>
                    </li>

                    {{-- ✅ Elemen user profile tetap dipertahankan di posisi berikutnya --}}
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
                        <li class="list-inline-item"><a href="{{ route('dashboard') }}">Home</a></li>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.drp-search input[type="search"]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.form.submit();
                    }
                });
            }
        });
    </script>

    {{-- ✅ Stack scripts tetap dipertahankan --}}
    @stack('scripts')

    {{-- ✅ SATU POLLING UNTUK SEMUA NOTIFIKASI --}}
    <script>
        function loadNotifikasi() {
            fetch("{{ route('ajax.notifikasi') }}")
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    const list = document.getElementById('notif-list');

                    // badge
                    if (data.total > 0) {
                        badge.innerText = data.total;
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.style.display = 'none';
                    }

                    // isi dropdown
                    list.innerHTML = '';

                    if (data.disposisi > 0) {
                        list.innerHTML += `
                            <a href="{{ route('disposisi.index') }}" class="dropdown-item">
                                📌 ${data.disposisi} Disposisi Baru
                            </a>
                        `;
                    }

                    if (data.total === 0) {
                        list.innerHTML = `
                            <span class="dropdown-item text-muted">
                                Tidak ada notifikasi baru
                            </span>
                        `;
                    }
                });
        }

        loadNotifikasi();
        setInterval(loadNotifikasi, 10000);
    </script>

</body>
</html>
