@extends('layouts.app')

@section('title', 'Rekap Surat')

@push('styles')
<style>
    .summary-card {
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: #fff;
    }

    .summary-card p {
        font-size: 14px;
        margin: 0;
        opacity: 0.9;
    }

    .summary-card h3 {
        font-size: 28px;
        font-weight: 600;
        margin-top: 6px;
    }

    .bg-blue {
        background: #4f8cff;
    }

    .bg-green {
        background: #2ecc71;
    }

    .bg-orange {
        background: #ff9800;
    }

    /* Table Styling */
    .table th,
    .table td {
        text-align: center;
        padding: 12px 18px;
    }

    .table th {
        background-color: #f1f3f9;
        font-weight: 600;
        font-size: 14px;
    }

    .table td {
        font-size: 14px;
        border-top: 1px solid #ddd;
    }

    .table thead {
        background-color: #e9ecef;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Home</a>
                    </li>
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
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('rekap-surat') }}">
            <div class="row align-items-end g-3">

                <div class="col-md-3">
                    <label class="form-label">Dari</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="end_date" class="form-control"
                           value="{{ request('end_date') }}">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>
                    <a href="{{ route('rekap-surat') }}" class="btn btn-light">
                        Reset
                    </a>
                </div>

                <div class="col-md-3 text-end">
                    <a href="{{ route('rekap-surat.export.pdf', request()->query()) }}"
                       class="btn btn-danger">
                        📄 PDF
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- RINGKASAN -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card summary-card bg-blue">
            <p>Total Surat Masuk</p>
            <h3>{{ $totalMasuk }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card summary-card bg-green">
            <p>Total Surat Keluar</p>
            <h3>{{ $totalKeluar }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card summary-card bg-orange">
            <p>Total Semua Surat</p>
            <h3>{{ $totalSurat }}</h3>
        </div>
    </div>
</div>

<!-- SURAT MASUK -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Surat Masuk</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data surat masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SURAT KELUAR -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Surat Keluar</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data surat keluar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
