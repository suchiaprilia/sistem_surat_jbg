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
                                <th>File Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->no_surat }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->tanggal_terima }}</td>
                                    <td>{{ $item->penerima }}</td>
                                    <td>{{ $item->pengirim }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ $item->tujuan }}</td>

                                    <td>
                                        @if ($item->file_surat)
                                            <a href="{{ asset('storage/surat/' . $item->file_surat) }}" target="_blank">
                                                Lihat
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <form action="{{ route('surat-masuk.destroy', $item->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada surat masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Surat -->
<div class="modal fade" id="suratModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Surat Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="suratForm" action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>No Surat</label>
                        <input type="text" name="no_surat" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Terima Surat</label>
                        <input type="date" name="tanggal_terima" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Penerima</label>
                        <input type="text" name="penerima" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Pengirim</label>
                        <input type="text" name="pengirim" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tujuan</label>
                        <input type="text" name="tujuan" class="form-control" required>
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
document.getElementById('btnTambah').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('suratModal'));
    modal.show();
});
</script>
@endsection
