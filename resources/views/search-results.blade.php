@extends('layouts.app')

@section('title', "Hasil Pencarian: {$query}")

@section('content')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Hasil Pencarian</li>
                    </ul>
                </div>
                <div class="col-md-12 mt-3">
                    <h2 class="page-header-title">
                        🔍 Hasil Pencarian: <span class="text-primary">"{{ $query }}"</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- SURAT MASUK -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Surat Masuk ({{ $suratMasuk->count() }})</h5>
        </div>
        <div class="card-body">
            @if($suratMasuk->isEmpty())
                <p class="text-muted">Tidak ditemukan surat masuk.</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No Surat</th>
                            <th>Pengirim</th>
                            <th>Perihal</th>
                            <th>Tanggal Terima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratMasuk as $s)
                        <tr>
                            <td>
                                <a href="{{ route('surat-masuk.show', $s->id) }}" class="text-decoration-none">
                                    {{ $s->no_surat }}
                                </a>
                            </td>
                            <td>{{ $s->pengirim ?? '-' }}</td>
                            <td>{{ Str::limit($s->subject, 30) }}</td>
                            <td>
                                {{ $s->tanggal_terima ? \Carbon\Carbon::parse($s->tanggal_terima)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- SURAT KELUAR -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Surat Keluar ({{ $suratKeluar->count() }})</h5>
        </div>
        <div class="card-body">
            @if($suratKeluar->isEmpty())
                <p class="text-muted">Tidak ditemukan surat keluar.</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No Surat</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratKeluar as $s)
                        <tr>
                            <td>
                                <a href="{{ route('surat-keluar.show', $s->id_surat_keluar) }}" class="text-decoration-none">
                                    {{ $s->no_surat_keluar }}
                                </a>
                            </td>
                            <td>{{ $s->destination ?? '-' }}</td>
                            <td>{{ Str::limit($s->subject, 30) }}</td>
                            <td>
                                {{ $s->date ? \Carbon\Carbon::parse($s->date)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
