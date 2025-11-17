@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Surat Keluar</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">📤 Surat Keluar</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Surat Keluar</h5>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Form Tambah/Edit -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>{{ isset($editData) ? '✏ Edit Surat Keluar' : '➕ Tambah Surat Keluar' }}</h5>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($editData) ? route('surat-keluar.update', $editData->id_surat_keluar) : route('surat-keluar.store') }}"
                            method="POST">
                            @csrf
                            @if(isset($editData))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_surat_keluar" class="form-label">No Surat</label>
                                    <input type="text" class="form-control" name="no_surat_keluar"
                                        value="{{ $editData->no_surat_keluar ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">Tujuan</label>
                                    <input type="text" class="form-control" name="destination"
                                        value="{{ $editData->destination ?? '' }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject"
                                    value="{{ $editData->subject ?? '' }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date"
                                    value="{{ $editData->date ?? '' }}" required>
                            </div>

                            <input type="hidden" name="id_user" value="1">
                            <input type="hidden" name="id_number_surat" value="1">

                            <button type="submit" class="btn btn-primary">
                                {{ isset($editData) ? 'Update' : 'Simpan' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Tujuan</th>
                                <th>Subject</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratKeluar as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_surat_keluar }}</td>
                                <td>{{ $item->destination }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('surat-keluar.edit', $item->id_surat_keluar) }}">Edit</a>
                                    <form action="{{ route('surat-keluar.destroy', $item->id_surat_keluar) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
