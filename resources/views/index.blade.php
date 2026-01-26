@extends('layouts.app')

@section('title', 'Dashboard - Sistem Surat')

@section('content')
<!-- [ Main Content ] start -->
<div class="row">
    <!-- Surat Masuk Hari Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-grd-primary order-card">
            <div class="card-body">
                <h6 class="text-white">Surat Masuk Hari Ini</h6>
                <h2 class="text-end text-white">
                    <i class="feather icon-mail float-start"></i>
                    <span>{{ $stats['masuk_hari_ini'] ?? 0 }}</span>
                </h2>
                <p class="m-b-0">Total Bulan Ini<span class="float-end">{{ $stats['total_masuk'] ?? 0 }}</span></p>
            </div>
        </div>
    </div>

    <!-- Surat Keluar Hari Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-grd-success order-card">
            <div class="card-body">
                <h6 class="text-white">Surat Keluar Hari Ini</h6>
                <h2 class="text-end text-white">
                    <i class="feather icon-send float-start"></i>
                    <span>{{ $stats['keluar_hari_ini'] ?? 0 }}</span>
                </h2>
                <p class="m-b-0">Total Bulan Ini<span class="float-end">{{ $stats['total_keluar'] ?? 0 }}</span></p>
            </div>
        </div>
    </div>

    <!-- Surat Belum Diproses (semua surat) -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-grd-warning order-card">
            <div class="card-body">
                <h6 class="text-white">Belum Diproses</h6>
                <h2 class="text-end text-white">
                    <i class="feather icon-clock float-start"></i>
                    <span>{{ $stats['pending'] ?? 0 }}</span>
                </h2>
                <p class="m-b-0">Total Surat Masuk<span class="float-end">{{ $stats['total_masuk'] ?? 0 }}</span></p>
            </div>
        </div>
    </div>

    <!-- Surat Mendesak (nonaktif sementara) -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-grd-danger order-card">
            <div class="card-body">
                <h6 class="text-white">Mendesak</h6>
                <h2 class="text-end text-white">
                    <i class="feather icon-alert-triangle float-start"></i>
                    <span>{{ $stats['urgent'] ?? 0 }}</span>
                </h2>
                <p class="m-b-0">Belum Tersedia<span class="float-end">—</span></p>
            </div>
        </div>
    </div>

    <!-- Grafik Surat per Bulan -->
    <div class="col-md-6 col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5>Statistik Surat (6 Bulan Terakhir)</h5>
            </div>
            <div class="card-body">
                {{-- ✅ FIX HEIGHT CHART --}}
                <div style="height:320px;">
                    <canvas id="suratChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan & Jenis Surat -->
    <div class="col-md-6 col-xl-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h5>Distribusi Jenis Surat</h5>
            </div>
            <div class="card-body">
                {{-- ✅ FIX HEIGHT CHART --}}
                <div style="height:320px;">
                    <canvas id="jenisSuratChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="media align-items-center">
                            <div class="avtar avtar-s bg-grd-primary flex-shrink-0">
                                <i class="feather icon-file-text text-white f-20"></i>
                            </div>
                            <div class="media-body ms-2">
                                <p class="mb-0 text-muted">Total Surat Masuk</p>
                                <h6 class="mb-0">{{ $stats['total_masuk'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="media align-items-center">
                            <div class="avtar avtar-s bg-grd-success flex-shrink-0">
                                <i class="feather icon-file-plus text-white f-20"></i>
                            </div>
                            <div class="media-body ms-2">
                                <p class="mb-0 text-muted">Total Surat Keluar</p>
                                <h6 class="mb-0">{{ $stats['total_keluar'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tambahan -->
    <div class="col-md-4 col-sm-6">
        <div class="card statistics-card-1">
            <div class="card-body">
                <img src="{{ asset('gradient/assets/images/widget/img-status-4.svg') }}" alt="img" class="img-fluid img-bg" />
                <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                    <h6 class="mb-0">Total Arsip Surat</h6>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ ($stats['total_masuk'] ?? 0) + ($stats['total_keluar'] ?? 0) }}</h3>
                </div>
                <p class="text-muted mb-2 text-sm mt-3">Gabungan surat masuk & keluar</p>
                <div class="progress" style="height: 7px">
                    <div class="progress-bar bg-brand-color-1" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card statistics-card-1">
            <div class="card-body">
                <img src="{{ asset('gradient/assets/images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg" />
                <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                    <h6 class="mb-0">Surat Hari Ini</h6>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <h3 class="f-w-300 d-flex align-items-center m-b-0">{{ ($stats['masuk_hari_ini'] ?? 0) + ($stats['keluar_hari_ini'] ?? 0) }}</h3>
                </div>
                <p class="text-muted mb-2 text-sm mt-3">Gabungan masuk & keluar hari ini</p>
                <div class="progress" style="height: 7px">
                    <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Arsip Digital (SUDAH AKTIF) --}}
    <div class="col-md-4 col-sm-12">
        <div class="card statistics-card-1 bg-brand-color-1">
            <div class="card-body">
                <img src="{{ asset('gradient/assets/images/widget/img-status-6.svg') }}" alt="img" class="img-fluid img-bg" />
                <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                    <h6 class="mb-0 text-white">Arsip Digital</h6>
                </div>

                <div class="d-flex align-items-center mt-3">
                    <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">
                        {{ $stats['digital'] ?? 0 }}
                    </h3>
                </div>

                <p class="text-white text-opacity-75 mb-2 text-sm mt-3">
                    {{ $stats['persen_digital'] ?? 0 }}% surat sudah diarsipkan
                </p>

                <div class="progress" style="height: 7px">
                    <div class="progress-bar bg-white" role="progressbar"
                        style="width: {{ $stats['persen_digital'] ?? 0 }}%"
                        aria-valuenow="{{ $stats['persen_digital'] ?? 0 }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terbaru -->
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Surat Masuk Terbaru</h5>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No Surat</th>
                                <th>Perihal</th>
                                <th>Tanggal Terima</th>
                                <th>Pengirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surat_pending as $s)
                            <tr>
                                <td><strong>{{ $s->no_surat ?? '-' }}</strong></td>
                                <td>{{ Str::limit($s->subject ?? '-', 30) }}</td>
                                <td>
                                    {{ $s->tanggal_terima ? \Carbon\Carbon::parse($s->tanggal_terima)->format('d M Y') : '-' }}
                                </td>
                                <td>{{ $s->pengirim ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Tidak ada surat masuk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ TABEL AGENDA HARI INI -->
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Agenda Hari Ini</h5>
                <a href="{{ url('/agenda?range=today') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>

            <div class="card-body p-0">
                @if(isset($agenda_hari_ini) && $agenda_hari_ini->count())
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Judul</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agenda_hari_ini as $a)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($a->tanggal_mulai)->format('H:i') }}</td>
                                        <td><strong>{{ $a->judul }}</strong></td>
                                        <td>{{ $a->lokasi ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $a->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-3 text-center text-muted">Tidak ada agenda hari ini</div>
                @endif
            </div>
        </div>
    </div>

</div>
<!-- [ Main Content ] end -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Surat per Bulan
    const ctx1 = document.getElementById('suratChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Surat Masuk',
                data: {!! json_encode($chartMasuk ?? []) !!},
                backgroundColor: 'rgba(33, 150, 243, 0.7)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 1
            }, {
                label: 'Surat Keluar',
                data: {!! json_encode($chartKeluar ?? []) !!},
                backgroundColor: 'rgba(76, 175, 80, 0.7)',
                borderColor: 'rgba(76, 175, 80, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Grafik Jenis Surat
    const ctx2 = document.getElementById('jenisSuratChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($jenisLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($jenisData ?? []) !!},
                backgroundColor: ['#2196f3', '#4caf50', '#ff9800', '#f44336', '#9c27b0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
