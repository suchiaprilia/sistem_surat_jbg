@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Surat Keluar</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">📤 Surat Keluar</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Surat Keluar</h5>
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
                    <table class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>No Surat</th>
                                <th>Tujuan</th>
                                <th>Subject</th>
                                <th>Tanggal</th>
                                <th>Requested By</th>
                                <th>Signed By</th>
                                <th style="width:120px;">File</th>
                                <th style="width:90px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($suratKeluar as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->no_surat_keluar }}</strong></td>
                                    <td>{{ $item->destination }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td>{{ $item->requested_by ?? '-' }}</td>
                                    <td>{{ $item->signed_by ?? '-' }}</td>

                                    <td>
                                        @if($item->file_scan)
                                            <a class="btn btn-sm btn-outline-primary"
                                               target="_blank"
                                               href="{{ asset('storage/'.$item->file_scan) }}">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-nowrap">
                                        <div class="dropdown position-static">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button
                                                        class="dropdown-item btn-edit"
                                                        type="button"
                                                        data-id="{{ $item->id_surat_keluar }}"
                                                        data-no="{{ $item->no_surat_keluar }}"
                                                        data-destination="{{ $item->destination }}"
                                                        data-subject="{{ $item->subject }}"
                                                        data-date="{{ $item->date }}"
                                                        data-requested="{{ $item->requested_by }}"
                                                        data-signed="{{ $item->signed_by }}"
                                                        data-jenis-id="{{ $item->id_jenis_surat }}"
                                                    >
                                                        Edit
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <form action="{{ route('surat-keluar.destroy', $item->id_surat_keluar) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus data ini?')">
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
                                    <td colspan="9" class="text-center text-muted py-3">Belum ada surat keluar.</td>
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
<div class="modal fade" id="suratModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suratModalLabel">Tambah Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="suratForm" action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_surat_keluar" id="id_surat_keluar">
                    <input type="hidden" name="id_user" value="1">

                    <div class="mb-3">
                        <label class="form-label">No Surat</label>
                        <input type="text" class="form-control" name="no_surat_keluar" id="no_surat_keluar"
                               placeholder="Contoh: 12/AH/JBG/I/2026" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <input type="text" class="form-control" name="destination" id="destination" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Requested By</label>
                        <input type="text" class="form-control" name="requested_by" id="requested_by">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Signed By</label>
                        <input type="text" class="form-control" name="signed_by" id="signed_by">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Surat</label>
                        <select name="id_jenis_surat" id="id_jenis_surat" class="form-control">
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach($jenisSurat as $jenis)
                                <option value="{{ $jenis->id_jenis_surat }}">{{ $jenis->jenis_surat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Scan (opsional)</label>
                        <input type="file" class="form-control" name="file_scan" id="file_scan">
                        <small class="text-muted">PDF/JPG/PNG max 5MB</small>
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

    document.getElementById('suratModalLabel').textContent = 'Tambah Surat Keluar';
    document.getElementById('suratForm').action = "{{ route('surat-keluar.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('suratForm').reset();
    document.getElementById('id_surat_keluar').value = '';
});

document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('suratModal'));
        modal.show();

        document.getElementById('suratModalLabel').textContent = 'Edit Surat Keluar';
        document.getElementById('suratForm').action = "/surat-keluar/" + btn.dataset.id;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('id_surat_keluar').value = btn.dataset.id;

        document.getElementById('no_surat_keluar').value = btn.dataset.no || '';
        document.getElementById('destination').value = btn.dataset.destination || '';
        document.getElementById('subject').value = btn.dataset.subject || '';
        document.getElementById('date').value = btn.dataset.date || '';
        document.getElementById('requested_by').value = btn.dataset.requested || '';
        document.getElementById('signed_by').value = btn.dataset.signed || '';
        document.getElementById('id_jenis_surat').value = btn.dataset.jenisId || '';
    });
});

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
