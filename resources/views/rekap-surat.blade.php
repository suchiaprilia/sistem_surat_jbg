@extends('layouts.app')

@section('title', 'Rekap Surat')

@push('styles')
<style>
    /* Warna solid sesuai contoh */
    .bg-blue-custom {
        background: #4f8cff;
        color: #fff;
    }

    .bg-green-custom {
        background: #2ecc71;
        color: #fff;
    }

    .bg-orange-custom {
        background: #ff9800;
        color: #fff;
    }

    /* Jika ingin teks hitam di dalam kotak berwarna, ganti color: #fff → color: #333 */
    /* Tapi karena contohmu teks putih, kita biarkan */

    .summary-card {
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .summary-card p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .summary-card h3 {
        font-size: 28px;
        margin: 6px 0 0;
        font-weight: bold;
    }

    /* Filter styling */
    .filter-box {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 16px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .filter-group label {
        font-size: 13px;
        color: #555;
        margin: 0;
    }

    .filter-group input {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .btn-filter {
        background: #4f8cff;
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .btn-filter:hover {
        background: #3a78f2;
    }

    .btn-reset {
        color: #4f8cff;
        text-decoration: none;
        font-size: 14px;
        display: inline-block;
        line-height: 38px;
        margin-left: 10px;
    }

    /* Table styling */
    .custom-table th {
        background: #f1f3f9;
        padding: 12px;
        font-size: 14px;
        text-align: left;
    }

    .custom-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-card h3 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Rekap Surat</li>
                    </ul>
                </div>
                <div class="col-md-12 mt-3">
                    <h2 class="page-header-title">📊 Rekap Surat</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <div class="filter-box">
        <form method="GET" action="{{ route('rekap-surat') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="filter-group">
                    <label>Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>
                <div>
                    <button type="submit" class="btn-filter">Filter</button>
                    <a href="{{ route('rekap-surat') }}" class="btn-reset">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- RINGKASAN (HANYA WARNA YANG DIUBAH) -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body summary-card bg-blue-custom">
                    <p>Total Surat Masuk</p>
                    <h3>{{ $totalMasuk }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body summary-card bg-green-custom">
                    <p>Total Surat Keluar</p>
                    <h3>{{ $totalKeluar }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body summary-card bg-orange-custom">
                    <p>Total Semua Surat</p>
                    <h3>{{ $totalMasuk + $totalKeluar }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- SURAT MASUK -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Surat Masuk</h5>
        </div>
        <div class="card-body">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>No Surat</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratMasuk as $s)
                    <tr>
                        <td>{{ $s->no_surat ?? '-' }}</td>
                        <td>{{ $s->pengirim ?? '-' }}</td>
                        <td>{{ $s->subject ?? '-' }}</td>
                        <td>{{ optional($s->jenisSurat)->jenis_surat ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SURAT KELUAR -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Surat Keluar</h5>
        </div>
        <div class="card-body">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>No Surat</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratKeluar as $s)
                    <tr>
                        <td>{{ $s->no_surat_keluar ?? '-' }}</td>
                        <td>{{ $s->destination ?? '-' }}</td>
                        <td>{{ $s->subject ?? '-' }}</td>
                        <td>{{ optional($s->jenisSurat)->jenis_surat ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
