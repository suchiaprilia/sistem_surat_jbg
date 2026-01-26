@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Surat Masuk</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">📩 Data Surat Masuk</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Daftar Surat Masuk</h5>
                <button class="btn btn-primary" id="btnTambah">+ Tambah Surat</button>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Tanggal</th>
                                <th>Tanggal Terima</th>
                                <th>Penerima</th>
                                <th>Pengirim</th>
                                <th>Subject</th>
                                <th>Tujuan</th>
                                <th>Jenis</th>
                                <th>File Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->no_surat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d-m-Y') }}</td>
                                    <td>{{ $item->penerima }}</td>
                                    <td>{{ $item->pengirim }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ $item->tujuan }}</td>
                                    <td>{{ $item->jenisSurat ? $item->jenisSurat->jenis_surat : '-' }}</td>
                                    <td>
                                        @if ($item->file_surat)
                                            <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                Lihat
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button
                                            class="btn btn-sm btn-warning btn-edit"
                                            data-id="{{ $item->id }}"
                                            data-no="{{ $item->no_surat }}"
                                            data-tanggal="{{ $item->tanggal }}"
                                            data-tanggal_terima="{{ $item->tanggal_terima }}"
                                            data-penerima="{{ $item->penerima }}"
                                            data-pengirim="{{ $item->pengirim }}"
                                            data-subject="{{ $item->subject }}"
                                            data-tujuan="{{ $item->tujuan }}"
                                            data-jenis-id="{{ $item->id_jenis_surat }}">
                                            Edit
                                        </button>
                                         <!-- Tombol Disposisi -->
                                        <a href="{{ route('disposisi.create', $item->id) }}"
                                        class="btn btn-sm btn-info">
                                        Disposisi
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('surat-masuk.destroy', $item->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada surat masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Surat -->
<div class="modal fade" id="suratModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suratModalLabel">Tambah Surat Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="suratForm" action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_surat_masuk" id="id_surat_masuk">

                    <div class="mb-3">
                        <label>No Surat</label>
                        <input type="text" name="no_surat" id="no_surat" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Terima Surat</label>
                        <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Penerima</label>
                        <input type="text" name="penerima" id="penerima" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Pengirim</label>
                        <input type="text" name="pengirim" id="pengirim" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tujuan</label>
                        <input type="text" name="tujuan" id="tujuan" class="form-control" required>
                    </div>

                    <!-- Dropdown Jenis Surat -->
                    <div class="mb-3">
                        <label>Jenis Surat</label>
                        <select name="id_jenis_surat" id="id_jenis_surat" class="form-control" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach($jenisSurat as $jenis)
                                <option value="{{ $jenis->id_jenis_surat }}">
                                    {{ $jenis->jenis_surat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>File Surat</label>
                        <input type="file" name="file_surat" class="form-control">
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
    const modal = new bootstrap.Modal(document.getElementById('suratModal'));
    modal.show();
    document.getElementById('suratModalLabel').textContent = 'Tambah Surat Masuk';
    document.getElementById('suratForm').action = "{{ route('surat-masuk.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('suratForm').reset();
    document.getElementById('id_surat_masuk').value = '';
});

// Tombol Edit
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('suratModal'));
        modal.show();
        document.getElementById('suratModalLabel').textContent = 'Edit Surat Masuk';
        document.getElementById('suratForm').action = "/surat-masuk/" + btn.dataset.id;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('id_surat_masuk').value = btn.dataset.id;
        document.getElementById('no_surat').value = btn.dataset.no;
        document.getElementById('tanggal').value = btn.dataset.tanggal;
        document.getElementById('tanggal_terima').value = btn.dataset.tanggal_terima;
        document.getElementById('penerima').value = btn.dataset.penerima;
        document.getElementById('pengirim').value = btn.dataset.pengirim;
        document.getElementById('subject').value = btn.dataset.subject;
        document.getElementById('tujuan').value = btn.dataset.tujuan;
        document.getElementById('id_jenis_surat').value = btn.dataset.jenisId;
    });
});

// Auto-hide success alert
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
