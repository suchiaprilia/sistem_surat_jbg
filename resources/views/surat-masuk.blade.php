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
                                    <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $item->tanggal_terima ? \Carbon\Carbon::parse($item->tanggal_terima)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $item->penerima }}</td>
                                    <td>{{ $item->pengirim }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ $item->tujuan }}</td>
                                    <td>{{ $item->jenisSurat ? $item->jenisSurat->jenis_surat : '-' }}</td>

                                    {{-- File --}}
                                    <td>
                                        @if ($item->file_surat)
                                            <a href="{{ route('surat-masuk.file', $item->id) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary">
                                                Lihat
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Aksi (Dropdown rapi seperti Surat Keluar) --}}
                                    <td class="text-nowrap">
                                        <div class="dropdown position-static">
                                            <button class="btn btn-sm btn-light border dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                {{-- Edit --}}
                                                <li>
                                                    <button
                                                        class="dropdown-item btn-edit"
                                                        type="button"
                                                        data-id="{{ $item->id }}"
                                                        data-no="{{ $item->no_surat }}"
                                                        data-tanggal="{{ $item->tanggal }}"
                                                        data-tanggal-terima="{{ $item->tanggal_terima }}"
                                                        data-penerima="{{ $item->penerima }}"
                                                        data-pengirim="{{ $item->pengirim }}"
                                                        data-subject="{{ $item->subject }}"
                                                        data-tujuan="{{ $item->tujuan }}"
                                                        data-jenis-id="{{ $item->id_jenis_surat }}">
                                                        ✏️ Edit
                                                    </button>
                                                </li>

                                                {{-- Disposisi --}}
                                                <li>
                                                    <a href="{{ route('disposisi.create', $item->id) }}"
                                                       class="dropdown-item text-info">
                                                        📤 Disposisi
                                                    </a>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Hapus --}}
                                                <li>
                                                    <form action="{{ route('surat-masuk.destroy', $item->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            🗑 Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
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

                    {{-- ✅ TAMBAHAN: BUAT AGENDA DARI SURAT (AUTO) --}}
                    <hr>
                    <div class="mb-2">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="buat_agenda" value="1" id="buatAgenda">
                            <span class="form-check-label">Buat agenda dari surat ini</span>
                        </label>
                    </div>

                    <div id="agendaFields" style="display:none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai Agenda (opsional)</label>
                                <input type="datetime-local" name="agenda_tanggal_mulai" class="form-control">
                                <small class="text-muted">Kalau kosong, otomatis pakai tanggal terima jam 08:00</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai (opsional)</label>
                                <input type="datetime-local" name="agenda_tanggal_selesai" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi (opsional)</label>
                            <input type="text" name="agenda_lokasi" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan (opsional)</label>
                            <textarea name="agenda_keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    {{-- ✅ END TAMBAHAN AGENDA --}}

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

    // reset agenda UI
    const cb = document.getElementById('buatAgenda');
    const box = document.getElementById('agendaFields');
    if (cb && box) {
        cb.checked = false;
        box.style.display = 'none';
    }
});

// Tombol Edit (dropdown item juga tetap class btn-edit)
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
        document.getElementById('tanggal_terima').value = btn.dataset.tanggalTerima;
        document.getElementById('penerima').value = btn.dataset.penerima;
        document.getElementById('pengirim').value = btn.dataset.pengirim;
        document.getElementById('subject').value = btn.dataset.subject;
        document.getElementById('tujuan').value = btn.dataset.tujuan;
        document.getElementById('id_jenis_surat').value = btn.dataset.jenisId;

        // agenda UI disembunyikan saat edit (karena agenda dibuat saat create)
        const cb = document.getElementById('buatAgenda');
        const box = document.getElementById('agendaFields');
        if (cb && box) {
            cb.checked = false;
            box.style.display = 'none';
        }
    });
});

// Toggle agenda fields
document.addEventListener('DOMContentLoaded', () => {
    const cb = document.getElementById('buatAgenda');
    const box = document.getElementById('agendaFields');
    if (cb && box) {
        cb.addEventListener('change', () => {
            box.style.display = cb.checked ? 'block' : 'none';
        });
    }
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
