@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Agenda</li>
                </ul>
            </div>

            <div class="col-md-12 mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="page-header-title mb-0">🗓️ Agenda</h2>

                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group" aria-label="Range agenda">
                        <a class="btn btn-sm btn-outline-primary {{ request('range')=='today' ? 'active' : '' }}"
                           href="{{ url('/agenda?range=today') }}">Hari ini</a>
                        <a class="btn btn-sm btn-outline-primary {{ request('range')=='week' ? 'active' : '' }}"
                           href="{{ url('/agenda?range=week') }}">Minggu ini</a>
                        <a class="btn btn-sm btn-outline-primary {{ request('range')=='month' ? 'active' : '' }}"
                           href="{{ url('/agenda?range=month') }}">Bulan ini</a>
                        <a class="btn btn-sm btn-outline-primary {{ !request('range') ? 'active' : '' }}"
                           href="{{ url('/agenda') }}">Semua</a>
                    </div>

                    <button class="btn btn-primary btn-sm" id="btnTambahAgenda">
                        + Tambah Agenda
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alert sukses --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Alert error --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <b>⚠️ Ada error:</b>
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-xl-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Agenda</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($agendas as $a)
                                <tr>
                                    <td><strong>{{ $a->judul }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($a->tanggal_mulai)->format('d M Y H:i') }}</td>
                                    <td>
                                        {{ $a->tanggal_selesai ? \Carbon\Carbon::parse($a->tanggal_selesai)->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td>{{ $a->lokasi ?? '-' }}</td>
                                    <td>
                                        @php $st = strtolower($a->status ?? ''); @endphp
                                        @if($st === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($st === 'ditunda')
                                            <span class="badge bg-warning text-dark">Ditunda</span>
                                        @elseif($st === 'dibatalkan')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-secondary">Terjadwal</span>
                                        @endif
                                    </td>

                                    {{-- Aksi: titik 3 --}}
                                    <td class="text-nowrap">
                                        <div class="dropdown position-static">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                ⋮
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                {{-- Tandai selesai --}}
                                                @if(strtolower($a->status) !== 'selesai')
                                                    <li>
                                                        <form method="POST" action="{{ url('/agenda/'.$a->id.'/done') }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                ✔ Tandai Selesai
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                @endif

                                                {{-- Hapus --}}
                                                <li>
                                                    <form method="POST" action="{{ url('/agenda/'.$a->id) }}"
                                                          onsubmit="return confirm('Yakin hapus agenda ini?')">
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
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada agenda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $agendas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Tambah Agenda --}}
<div class="modal fade" id="agendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agendaModalLabel">Tambah Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ url('/agenda') }}" id="agendaForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai" class="form-control"
                                   value="{{ old('tanggal_mulai') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai (opsional)</label>
                            <input type="datetime-local" name="tanggal_selesai" class="form-control"
                                   value="{{ old('tanggal_selesai') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi (opsional)</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan (opsional)</label>
                        <textarea name="keterangan" rows="3" class="form-control">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="terjadwal" {{ old('status')=='terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                            <option value="ditunda" {{ old('status')=='ditunda' ? 'selected' : '' }}>Ditunda</option>
                            <option value="dibatalkan" {{ old('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="selesai" {{ old('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
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
    // tombol tambah -> buka modal
    document.getElementById('btnTambahAgenda').addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('agendaModal'));
        modal.show();

        // reset simple (opsional)
        document.getElementById('agendaModalLabel').textContent = 'Tambah Agenda';
        document.getElementById('agendaForm').reset();
    });

    // auto hide success alert
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 3000);
        }
    });

    // kalau ada error validasi, auto buka modal biar user lihat fieldnya
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('agendaModal'));
            modal.show();
        });
    @endif
</script>
@endsection
