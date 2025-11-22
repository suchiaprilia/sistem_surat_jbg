@extends('layouts.app')

@section('title', 'Data Jabatan')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Jabatan</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">👔 Data Jabatan</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Manajemen Jabatan</h5>
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
                        <h5>{{ isset($editData) ? '✏ Edit Jabatan' : '➕ Tambah Jabatan' }}</h5>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($editData) ? route('jabatan.update', $editData->id_jabatan) : route('jabatan.store') }}"
                            method="POST">
                            @csrf
                            @if(isset($editData))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input type="text" class="form-control" name="nama_jabatan"
                                    value="{{ $editData->nama_jabatan ?? '' }}" required>
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
                                <th>Nama Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jabatans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_jabatan }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="{{ route('jabatan.edit', $item->id_jabatan) }}">Edit</a>
                                        <form action="{{ route('jabatan.destroy', $item->id_jabatan) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jabatan ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data jabatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
