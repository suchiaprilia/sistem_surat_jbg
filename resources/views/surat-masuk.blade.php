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

                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabel Daftar Surat Masuk -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Dari</th>
                                <th>Subject</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suratMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->no_surat_masuk }}</td>
                                    <td>{{ $item->from }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning btn-edit"
                                            data-id="{{ $item->id_surat_masuk }}"
                                            data-no="{{ $item->no_surat_masuk }}"
                                            data-from="{{ $item->from }}"
                                            data-email="{{ $item->tujuan_email }}"
                                            data-subject="{{ $item->subject }}"
                                            data-received="{{ $item->received_by }}"
                                            data-user="{{ $item->id_user }}"
                                            data-date="{{ $item->date }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('surat-masuk.destroy', $item->id_surat_masuk) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada surat masuk.</td>
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
                <h5 class="modal-title" id="suratModalLabel">Tambah Surat Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="suratForm" action="{{ route('surat-masuk.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id_surat_masuk" id="id_surat_masuk">

                    <div class="mb-3">
                        <label for="no_surat_masuk" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" name="no_surat_masuk" id="no_surat_masuk" placeholder="Nomor Surat" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">Dari</label>
                        <input type="text" class="form-control" name="from" id="from" placeholder="Dari" required>
                    </div>
                    <div class="mb-3">
                        <label for="tujuan_email" class="form-label">Tujuan Email</label>
                        <input type="email" class="form-control" name="tujuan_email" id="tujuan_email" placeholder="Tujuan Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subjek" required>
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Diterima oleh</label>
                        <input type="text" class="form-control" name="received_by" id="received_by" placeholder="Diterima oleh" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_user" class="form-label">ID User</label>
                        <input type="number" class="form-control" name="id_user" id="id_user" placeholder="ID User" required>
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
            document.getElementById('no_surat_masuk').value = btn.dataset.no;
            document.getElementById('from').value = btn.dataset.from;
            document.getElementById('tujuan_email').value = btn.dataset.email;
            document.getElementById('subject').value = btn.dataset.subject;
            document.getElementById('received_by').value = btn.dataset.received;
            document.getElementById('id_user').value = btn.dataset.user;
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
            }, 3000); // 5000ms = 5 detik
        }
    });
</script>
@endsection
