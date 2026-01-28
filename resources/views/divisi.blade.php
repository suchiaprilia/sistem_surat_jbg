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
                <button class="btn btn-primary" id="btnTambah">+ Tambah Divisi</button>
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
                                <th>Nama Divisi</th>
                                <th>Kode</th>
                                <th>Kepala Divisi</th>
                                <th>Kontak</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($divisis as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_divisi }}</td>
                                    <td>{{ $item->kode_divisi }}</td>
                                    <td>{{ $item->kepala_divisi }}</td>
                                    <td>{{ $item->kontak }}</td>

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
                                                        data-id="{{ $item->id_divisi }}"
                                                        data-nama="{{ $item->nama_divisi }}"
                                                        data-kode="{{ $item->kode_divisi }}"
                                                        data-kepala="{{ $item->kepala_divisi }}"
                                                        data-kontak="{{ $item->kontak }}"
                                                        data-deskripsi="{{ $item->deskripsi }}">
                                                        Edit
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Hapus --}}
                                                <li>
                                                    <form action="{{ route('divisi.destroy', $item->id_divisi) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus divisi ini?')">
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
                                    <td colspan="6" class="text-center">Belum ada data divisi.</td>
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
<div class="modal fade" id="divisiModal" tabindex="-1" aria-labelledby="divisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="divisiModalLabel">Tambah Divisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="divisiForm" action="{{ route('divisi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_divisi" id="id_divisi">

                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" name="nama_divisi" id="nama_divisi" placeholder="Contoh: Sekretariat" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode Divisi</label>
                        <input type="text" class="form-control" name="kode_divisi" id="kode_divisi" placeholder="Contoh: SEK">
                    </div>
                    <div class="mb-3">
                        <label for="kepala_divisi" class="form-label">Kepala Divisi</label>
                        <input type="text" class="form-control" name="kepala_divisi" id="kepala_divisi" placeholder="Nama kepala divisi">
                    </div>
                    <div class="mb-3">
                        <label for="kontak" class="form-label">Kontak</label>
                        <input type="text" class="form-control" name="kontak" id="kontak" placeholder="Nomor telepon / email">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Deskripsi singkat divisi"></textarea>
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
        const modal = new bootstrap.Modal(document.getElementById('divisiModal'));
        modal.show();
        document.getElementById('divisiModalLabel').textContent = 'Tambah Divisi';
        document.getElementById('divisiForm').action = "{{ route('divisi.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('divisiForm').reset();
        document.getElementById('id_divisi').value = '';
    });

    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('divisiModal'));
            modal.show();
            document.getElementById('divisiModalLabel').textContent = 'Edit Divisi';
            document.getElementById('divisiForm').action = "/divisi/" + btn.dataset.id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('id_divisi').value = btn.dataset.id;
            document.getElementById('nama_divisi').value = btn.dataset.nama;
            document.getElementById('kode_divisi').value = btn.dataset.kode;
            document.getElementById('kepala_divisi').value = btn.dataset.kepala;
            document.getElementById('kontak').value = btn.dataset.kontak;
            document.getElementById('deskripsi').value = btn.dataset.deskripsi;
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
