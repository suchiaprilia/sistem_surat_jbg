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
            <div class="card-header">
                <h5>Data Surat Keluar</h5>
                <button class="btn btn-primary" id="btnTambah">+ Tambah Surat</button>
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
                                <th>No Surat</th>
                                <th>Tujuan</th>
                                <th>Subject</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratKeluar as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->no_surat_keluar }}</td>
                                    <td>{{ $item->destination }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning btn-edit"
                                            data-id="{{ $item->id_surat_keluar }}"
                                            data-no="{{ $item->no_surat_keluar }}"
                                            data-destination="{{ $item->destination }}"
                                            data-subject="{{ $item->subject }}"
                                            data-date="{{ $item->date }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('surat-keluar.destroy', $item->id_surat_keluar) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada surat keluar.</td>
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
<div class="modal fade" id="suratModal" tabindex="-1" aria-labelledby="suratModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suratModalLabel">Tambah Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="suratForm" action="{{ route('surat-keluar.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_surat_keluar" id="id_surat_keluar">
                    <input type="hidden" name="id_user" value="1">
                    <input type="hidden" name="id_number_surat" value="1">

                    <div class="mb-3">
                        <label for="no_surat_keluar" class="form-label">No Surat</label>
                        <input type="text" class="form-control" name="no_surat_keluar" id="no_surat_keluar" placeholder="Nomor Surat" required>
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Tujuan</label>
                        <input type="text" class="form-control" name="destination" id="destination" placeholder="Tujuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subjek" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="date" id="date" required>
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
        document.getElementById('suratModalLabel').textContent = 'Tambah Surat Keluar';
        document.getElementById('suratForm').action = "{{ route('surat-keluar.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('suratForm').reset();
        document.getElementById('id_surat_keluar').value = '';
    });

    // Tombol Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('suratModal'));
            modal.show();
            document.getElementById('suratModalLabel').textContent = 'Edit Surat Keluar';
            document.getElementById('suratForm').action = "/surat-keluar/" + btn.dataset.id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('id_surat_keluar').value = btn.dataset.id;
            document.getElementById('no_surat_keluar').value = btn.dataset.no;
            document.getElementById('destination').value = btn.dataset.destination;
            document.getElementById('subject').value = btn.dataset.subject;
            document.getElementById('date').value = btn.dataset.date;
        });
    });

    // Auto-hide success alert setelah 5 detik
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
