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
                @if(session('role')=='admin')
                <button class="btn btn-primary" id="btnTambah">+ Tambah Jabatan</button>
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
                                <th>Nama Jabatan</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jabatans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_jabatan }}</td>

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
                                                        data-id="{{ $item->id_jabatan }}"
                                                        data-nama="{{ $item->nama_jabatan }}">
                                                        Edit
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Hapus --}}
                                                <li>
                                                    <form action="{{ route('jabatan.destroy', $item->id_jabatan) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus jabatan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
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

<!-- Modal Form Tambah/Edit -->
<div class="modal fade" id="jabatanModal" tabindex="-1" aria-labelledby="jabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jabatanModalLabel">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="jabatanForm" action="{{ route('jabatan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_jabatan" id="id_jabatan">

                    <div class="mb-3">
                        <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control" name="nama_jabatan" id="nama_jabatan" placeholder="Contoh: Sekretaris" required>
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
    // Tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('jabatanModal'));
        modal.show();
        document.getElementById('jabatanModalLabel').textContent = 'Tambah Jabatan';
        document.getElementById('jabatanForm').action = "{{ route('jabatan.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('jabatanForm').reset();
        document.getElementById('id_jabatan').value = '';
    });

    // Tombol Edit (tetap jalan karena class btn-edit masih sama)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('jabatanModal'));
            modal.show();
            document.getElementById('jabatanModalLabel').textContent = 'Edit Jabatan';
            document.getElementById('jabatanForm').action = "/jabatan/" + btn.dataset.id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('id_jabatan').value = btn.dataset.id;
            document.getElementById('nama_jabatan').value = btn.dataset.nama;
        });
    });

    // Auto-hide success alert dalam 3 detik
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 3000);
        }
    });
</script>
@endsection
