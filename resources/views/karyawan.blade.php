@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Karyawan</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">👨‍💼 Data Karyawan</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Manajemen Karyawan</h5>
                 @if(session('role')=='admin')
                <button class="btn btn-primary" id="btnTambah">+ Tambah Karyawan</button>
                @endif
            </div>
            <div class="card-body">

                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_karyawan }}</td>
                                    <td>{{ $item->email_karyawan }}</td>
                                    <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                                    <td>{{ $item->jabatan->nama_jabatan ?? '-' }}</td>

                                    {{-- ✅ AKSI TITIK TIGA --}}
                                    <td class="text-nowrap">
                                        <div class="dropdown position-static">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                {{-- Edit --}}
                                                <li>
                                                    <button
                                                        class="dropdown-item btn-edit"
                                                        type="button"
                                                        data-id="{{ $item->id_karyawan }}"
                                                        data-nama="{{ $item->nama_karyawan }}"
                                                        data-email="{{ $item->email_karyawan }}"
                                                        data-divisi="{{ $item->id_divisi }}"
                                                        data-jabatan="{{ $item->id_jabatan }}">
                                                        Edit
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Hapus --}}
                                                <li>
                                                    <form action="{{ route('karyawan.destroy', $item->id_karyawan) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus data?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                                {{-- Reset Password --}}
@if(session('role')=='admin')
<li>
    <form action="{{ route('karyawan.resetPassword', $item->id_karyawan) }}"
          method="POST"
          onsubmit="return confirm('Reset password akun login jadi 123456?')">
        @csrf
        <button type="submit" class="dropdown-item">
            Reset Password
        </button>
    </form>
</li>

<li><hr class="dropdown-divider"></li>
@endif

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data karyawan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Tambah/Edit -->
<div class="modal fade" id="karyawanModal" tabindex="-1" aria-labelledby="karyawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  @if(session('role')=='admin')
                <h5 class="modal-title" id="karyawanModalLabel">Tambah Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             @endif
            </div>
            <div class="modal-body">
                <form id="karyawanForm" action="{{ route('karyawan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_karyawan" id="id_karyawan">

                    <div class="mb-3">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan"
                               pattern="[A-Za-z\s]+" title="Nama hanya boleh huruf." required>
                        <div class="invalid-feedback text-danger" id="error_nama_karyawan"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email_karyawan" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email_karyawan" id="email_karyawan" required>
                        <div class="invalid-feedback text-danger" id="error_email_karyawan"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_divisi" class="form-label">Divisi</label>
                            <select class="form-control" name="id_divisi" id="id_divisi" required>
                                <option value="">— Pilih Divisi —</option>
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_jabatan" class="form-label">Jabatan</label>
                            <select class="form-control" name="id_jabatan" id="id_jabatan" required>
                                <option value="">— Pilih Jabatan —</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Reset error messages
    function clearErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('karyawanModal'));
        modal.show();
        document.getElementById('karyawanModalLabel').textContent = 'Tambah Karyawan';
        document.getElementById('karyawanForm').action = "{{ route('karyawan.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('karyawanForm').reset();
        document.getElementById('id_karyawan').value = '';
        clearErrors();
    });

    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('karyawanModal'));
            modal.show();
            document.getElementById('karyawanModalLabel').textContent = 'Edit Karyawan';
            document.getElementById('karyawanForm').action = "/karyawan/" + btn.dataset.id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('id_karyawan').value = btn.dataset.id;
            document.getElementById('nama_karyawan').value = btn.dataset.nama;
            document.getElementById('email_karyawan').value = btn.dataset.email;
            document.getElementById('id_divisi').value = btn.dataset.divisi;
            document.getElementById('id_jabatan').value = btn.dataset.jabatan;
            clearErrors();
        });
    });

    // Auto-hide success alert dalam 3 detik
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 3000); // ✅ 3 detik
        }
    });
</script>
@endsection
