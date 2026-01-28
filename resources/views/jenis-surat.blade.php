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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manajemen Jenis Surat</h5>
                <button class="btn btn-primary" id="btnTambah">+ Tambah Jenis</button>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="80">No</th>
                                <th>Jenis Surat</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisSurats as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->jenis_surat }}</td>

                                    {{-- ✅ AKSI TITIK TIGA --}}
                                    <td class="text-center">
                                        <div class="dropdown position-static d-inline-block">
                                            <button class="btn btn-sm btn-light border dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button
                                                        class="dropdown-item btn-edit"
                                                        type="button"
                                                        data-id="{{ $item->id_jenis_surat }}"
                                                        data-jenis="{{ $item->jenis_surat }}">
                                                        Edit
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <form action="{{ route('jenis-surat.destroy', $item->id_jenis_surat) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus jenis surat ini?')">
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
                                    <td colspan="3" class="text-center text-muted">
                                        Belum ada data jenis surat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="jenisSuratModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisSuratModalLabel">Tambah Jenis Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="jenisSuratForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3">
                        <label class="form-label">Jenis Surat</label>
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
        const modal = new bootstrap.Modal(document.getElementById('jenisSuratModal'));
        modal.show();

        document.getElementById('jenisSuratModalLabel').textContent = 'Edit Jenis Surat';
        document.getElementById('jenisSuratForm').action = `/jenis-surat/${btn.dataset.id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('jenis_surat').value = btn.dataset.jenis;
    });
});

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
