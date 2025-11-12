<!doctype html>
<html lang="id">
<head>
    <title>Dashboard - Sistem Manajemen Surat Internal</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Sistem Manajemen Surat Perusahaan" />
    <link rel="icon" href="{{ asset('gradient/assets/images/favicon.svg') }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style-preset.css') }}" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .agenda-item {
            border-left: 3px solid #4361ee;
            padding-left: 12px;
            margin-bottom: 12px;
        }
        .agenda-item.disposed {
            border-left-color: #06d6a0;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff6b6b;
            color: white;
            font-size: 0.7rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light"
      data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

<!-- Sidebar & Header (minimal) -->
<div class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-head-link">
            <a href="#" class="pc-head-link-item">
                <img src="{{ asset('gradient/assets/images/logo-dark.svg') }}" alt="logo" class="logo-lg" />
            </a>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled mb-0 d-flex align-items-center">
                <li class="dropdown">
                    <a class="pc-head-link-item" href="#" data-bs-toggle="dropdown">
                        <i class="ti ti-bell fs-5"></i>
                        <span class="badge bg-danger notification-badge">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-notification">
                        <div class="dropdown-header">Notifikasi Disposisi</div>
                        <div class="dropdown-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-file-text text-primary fs-6"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0">Surat No. 021/SM/IV/2025 perlu disposisi</p>
                                    <small class="text-muted">2 jam lalu</small>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <a href="/surat-masuk?status=menunggu">Lihat Semua</a>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="pc-head-link-item" href="#">
                        <img src="{{ asset('gradient/assets/images/user/avatar-1.jpg') }}" alt="user" class="img-radius" width="36" height="36" />
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="navbar-content">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link active"><i class="ti ti-home"></i><span>Dashboard</span></a>
                </li>
                <li class="nav-item">
                    <a href="/surat-masuk" class="nav-link"><i class="ti ti-inbox"></i><span>Surat Masuk</span></a>
                </li>
                <li class="nav-item">
                    <a href="/surat-keluar" class="nav-link"><i class="ti ti-send"></i><span>Surat Keluar</span></a>
                </li>
                <li class="nav-item">
                    <a href="/disposisi" class="nav-link"><i class="ti ti-file-check"></i><span>Disposisi</span></a>
                </li>
                <li class="nav-item">
                    <a href="/agenda" class="nav-link"><i class="ti ti-calendar"></i><span>Agenda Digital</span></a>
                </li>
                <li class="nav-item">
                    <a href="/laporan" class="nav-link"><i class="ti ti-report"></i><span>Laporan</span></a>
                </li>
                <li class="nav-item">
                    <a href="/user" class="nav-link"><i class="ti ti-users"></i><span>Manajemen User</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Content -->
<div class="pc-container">
    <div class="pc-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item">Dashboard</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Dashboard Manajemen Surat</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1">Surat Masuk Hari Ini</p>
                                <h4 class="mb-0">12</h4>
                            </div>
                            <div class="icon">
                                <i class="ti ti-inbox fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1">Surat Keluar Hari Ini</p>
                                <h4 class="mb-0">5</h4>
                            </div>
                            <div class="icon">
                                <i class="ti ti-send fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1">Menunggu Disposisi</p>
                                <h4 class="mb-0">8</h4>
                            </div>
                            <div class="icon">
                                <i class="ti ti-file-check fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1">Surat Tertunda</p>
                                <h4 class="mb-0">3</h4>
                            </div>
                            <div class="icon">
                                <i class="ti ti-alert-triangle fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Grafik -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Statistik Surat Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="letterChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Agenda -->
                <div class="card">
                    <div class="card-header">
                        <h5>Agenda Hari Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="agenda-item">
                            <strong>09.00</strong> – Paraf surat keluar ke Direktur (No. 022/SK/IV/2025)
                        </div>
                        <div class="agenda-item disposed">
                            <strong>10.30</strong> – Disposisi surat masuk dari Dinas Pendidikan (No. 456/SP/IV/2025)
                        </div>
                        <div class="agenda-item">
                            <strong>14.00</strong> – Rapat koordinasi agenda surat mingguan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Logs -->
            <div class="col-xl-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5>Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <a href="/surat-masuk/tambah" class="btn btn-light w-100 mb-2 text-start">
                            <i class="ti ti-plus me-2"></i> Tambah Surat Masuk
                        </a>
                        <a href="/surat-keluar/tambah" class="btn btn-light w-100 mb-2 text-start">
                            <i class="ti ti-send me-2"></i> Buat Surat Keluar
                        </a>
                        <a href="/disposisi" class="btn btn-light w-100 mb-2 text-start">
                            <i class="ti ti-file-check me-2"></i> Kelola Disposisi
                        </a>
                        <a href="/laporan" class="btn btn-light w-100 text-start">
                            <i class="ti ti-report me-2"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                <!-- Audit Log -->
                <div class="card">
                    <div class="card-header">
                        <h5>Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <small class="text-muted">10.25</small> – Surat No. 021/SM disetujui oleh <strong>Budi</strong>
                            </li>
                            <li class="list-group-item">
                                <small class="text-muted">09.40</small> – Surat No. 020/SK dikirim ke Client
                            </li>
                            <li class="list-group-item">
                                <small class="text-muted">08.15</small> – Surat masuk dari Dispendik diterima
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('gradient/assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('gradient/assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('gradient/assets/js/script.js') }}"></script>
<script>
    // Grafik Surat
    const ctx = document.getElementById('letterChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
         {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [
                {
                    label: 'Surat Masuk',
                    data: [45, 52, 48, 61, 58, 72],
                    backgroundColor: '#4361ee'
                },
                {
                    label: 'Surat Keluar',
                    data: [30, 38, 35, 42, 40, 55],
                    backgroundColor: '#06d6a0'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>