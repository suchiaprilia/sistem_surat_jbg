@extends('layouts.app')

@section('title', 'Data Divisi')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Divisi</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">🏢 Data Divisi</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Manajemen Divisi</h5>
                <a href="{{ route('dashboard') }}" class="btn btn-success">← Kembali ke Dashboard</a>
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
                        <h5>{{ isset($editData) ? '✏ Edit Divisi' : '➕ Tambah Divisi' }}</h5>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($editData) ? route('divisi.update', $editData->id_divisi) : route('divisi.store') }}"
                            method="POST">
                            @csrf
                            @if(isset($editData))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_divisi" class="form-label">Nama Divisi</label>
                                    <input type="text" class="form-control" name="nama_divisi"
                                        value="{{ $editData->nama_divisi ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kode_divisi" class="form-label">Kode Divisi</label>
                                    <input type="text" class="form-control" name="kode_divisi"
                                        value="{{ $editData->kode_divisi ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kepala_divisi" class="form-label">Kepala Divisi</label>
                                    <input type="text" class="form-control" name="kepala_divisi"
                                        value="{{ $editData->kepala_divisi ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kontak" class="form-label">Kontak</label>
                                    <input type="text" class="form-control" name="kontak"
                                        value="{{ $editData->kontak ?? '' }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3">{{ $editData->deskripsi ?? '' }}</textarea>
                            </div>

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
                                <th>Nama Divisi</th>
                                <th>Kode</th>
                                <th>Kepala Divisi</th>
                                <th>Kontak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divisis as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_divisi }}</td>
                                <td>{{ $item->kode_divisi }}</td>
                                <td>{{ $item->kepala_divisi }}</td>
                                <td>{{ $item->kontak }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('divisi.edit', $item->id_divisi) }}">Edit</a>
                                    <form action="{{ route('divisi.destroy', $item->id_divisi) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus divisi ini?')">Hapus</button>
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
