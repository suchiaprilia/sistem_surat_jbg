@extends('layouts.app')

@section('title', 'Jenis Surat')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Jenis Surat</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">📄 Jenis Surat</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Manajemen Jenis Surat</h5>
                <button class="btn btn-primary" id="btnTambah">+ Tambah Jenis</button>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisSurats as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->jenis_surat }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning btn-edit"
                                            data-id="{{ $item->id_jenis_surat }}"
                                            data-jenis="{{ $item->jenis_surat }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('jenis-surat.destroy', $item->id_jenis_surat) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jenis surat ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data jenis surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="jenisSuratModal" tabindex="-1" aria-labelledby="jenisSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisSuratModalLabel">Tambah Jenis Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="jenisSuratForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3">
                        <label for="jenis_surat" class="form-label">Jenis Surat</label>
                        <input type="text" class="form-control" name="jenis_surat" id="jenis_surat" required>
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
        const modal = new bootstrap.Modal(document.getElementById('jenisSuratModal'));
        modal.show();
        document.getElementById('jenisSuratModalLabel').textContent = 'Tambah Jenis Surat';
        document.getElementById('jenisSuratForm').action = "{{ route('jenis-surat.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('jenis_surat').value = '';
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const jenis = btn.dataset.jenis;
            const modal = new bootstrap.Modal(document.getElementById('jenisSuratModal'));
            modal.show();
            document.getElementById('jenisSuratModalLabel').textContent = 'Edit Jenis Surat';
            document.getElementById('jenisSuratForm').action = `/jenis-surat/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('jenis_surat').value = jenis;
        });
    });

    // Auto-hide success alert
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(() => {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }, 3000);
        }
    });
</script>
@endsection
