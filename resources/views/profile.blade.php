@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Profil</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">👤 Profil Saya</h2>
            </div>
        </div>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 ps-3 mt-2">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    {{-- KIRI: INFO PROFIL --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Informasi Akun</h5>
                <span class="badge bg-primary text-uppercase">{{ $user->role ?? '-' }}</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ asset('gradient/assets/images/user/avatar-2.jpg') }}" class="rounded-circle" width="56" height="56" alt="avatar">
                    <div>
                        <h5 class="mb-0">{{ $user->nama ?? '-' }}</h5>
                        <div class="text-muted">{{ $user->email ?? '-' }}</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th style="width: 140px;">Nama</th>
                            <td>{{ $user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td class="text-uppercase">{{ $user->role ?? '-' }}</td>
                        </tr>

                        <tr><td colspan="2"><hr class="my-2"></td></tr>

                        <tr>
                            <th>Divisi</th>
                            <td>{{ $karyawan->divisi->nama_divisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                        </tr>

                        <tr><td colspan="2"><hr class="my-2"></td></tr>

                        <tr>
                            <th>Dibuat</th>
                            <td>{{ optional($user->created_at)->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Update</th>
                            <td>{{ optional($user->updated_at)->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="mt-3 text-muted small">
                    Jika data divisi/jabatan kosong, pastikan email di tabel <b>karyawans</b> sama dengan email akun login (<b>user</b>).
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: GANTI PASSWORD (DISIMPAN DI COLLAPSE) --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Password</h5>

                {{-- Tombol untuk munculin form --}}
                <button class="btn btn-sm btn-outline-primary"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsePassword"
                        aria-expanded="false"
                        aria-controls="collapsePassword">
                    Ubah Password
                </button>
            </div>

            <div class="card-body">

                {{-- Tampilan awal --}}
                <div class="text-muted mb-3">
                    Klik tombol <b>Ubah Password</b> untuk menampilkan form.
                </div>

                {{-- Form disembunyikan dulu, tapi kalau ada error/success otomatis kebuka --}}
                <div class="collapse @if($errors->any() || session('success') || session('error')) show @endif" id="collapsePassword">

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control" required>
                            <div class="form-text">Masukkan password lama untuk verifikasi.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" minlength="4" required>
                            <div class="form-text">Minimal 4 karakter.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi" class="form-control" minlength="4" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan Password
                            </button>
                            <button type="reset" class="btn btn-light">
                                Reset Form
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
