@extends('layouts.app')

@section('title', 'Disposisi Saya')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">📥 Disposisi Saya</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:160px">No Surat</th>
                            <th style="width:140px">Pengirim</th>
                            <th style="width:260px">Instruksi</th>
                            <th style="width:110px">Batas Waktu</th>
                            <th style="width:90px">Status</th>
                            <th style="width:90px">File</th>
                            <th style="width:140px">Aksi</th>
                            <th style="width:110px">Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disposisis as $disposisi)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>{{ $disposisi->suratMasuk->no_surat ?? '-' }}</td>

                                <td>{{ $disposisi->dari->nama_karyawan ?? '-' }}</td>

                                {{-- INSTRUKSI (dibatasi biar ga melebar) --}}
                                <td style="white-space:normal;">
                                    {{ $disposisi->instruksi }}
                                </td>

                                <td class="text-center">
                                    {{ $disposisi->batas_waktu
                                        ? \Carbon\Carbon::parse($disposisi->batas_waktu)->format('d-m-Y')
                                        : '-' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    @if ($disposisi->status === 'baru')
                                        <span class="badge bg-secondary">BARU</span>
                                    @elseif ($disposisi->status === 'dibaca')
                                        <span class="badge bg-warning">DIBACA</span>
                                    @elseif ($disposisi->status === 'selesai')
                                        <span class="badge bg-success">SELESAI</span>
                                    @endif
                                </td>

                                {{-- FILE SURAT (ikon biar ringkas) --}}
                                <td class="text-center">
                                    @if($disposisi->suratMasuk && $disposisi->suratMasuk->file_surat)
                                        <a href="{{ asset('storage/'.$disposisi->suratMasuk->file_surat) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">
                                            📄
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- AKSI (ditumpuk vertikal) --}}
                                <td>
                                    <div class="d-grid gap-1">

                                        @if ($disposisi->status === 'baru')
                                            <form action="{{ route('disposisi.dibaca', $disposisi->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-info w-100">
                                                    Dibaca
                                                </button>
                                            </form>
                                        @endif

                                        @if ($disposisi->status !== 'selesai')
                                            <form action="{{ route('disposisi.selesai', $disposisi->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success w-100">
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif

                                        @if ($disposisi->status !== 'selesai')
                                            <a href="{{ route('disposisi.teruskan', $disposisi->id) }}"
                                               class="btn btn-sm btn-warning w-100">
                                                Teruskan
                                            </a>
                                        @endif

                                    </div>
                                </td>

                                <td class="text-center">
                                    {{ $disposisi->created_at->format('d-m-Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Belum ada disposisi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
