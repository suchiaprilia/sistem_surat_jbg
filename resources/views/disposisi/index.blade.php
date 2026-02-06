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

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Pengirim</th>
                        <th>Instruksi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th style="width:180px">Aksi</th>
                        <th>Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disposisis as $disposisi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $disposisi->suratMasuk->no_surat ?? '-' }}
                            </td>

                            <td>
                                {{ $disposisi->dari->nama_karyawan ?? '-' }}
                            </td>

                            <td>
                                {{ $disposisi->instruksi }}
                            </td>

                            <td>
                                {{ $disposisi->batas_waktu
                                    ? \Carbon\Carbon::parse($disposisi->batas_waktu)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($disposisi->status === 'baru')
                                    <span class="badge bg-secondary">BARU</span>
                                @elseif ($disposisi->status === 'dibaca')
                                    <span class="badge bg-warning">DIBACA</span>
                                @elseif ($disposisi->status === 'selesai')
                                    <span class="badge bg-success">SELESAI</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-nowrap">

                                {{-- TANDAI DIBACA --}}
                                @if ($disposisi->status === 'baru')
                                    <form action="{{ route('disposisi.dibaca', $disposisi->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-info">
                                            Dibaca
                                        </button>
                                    </form>
                                @endif

                                {{-- SELESAI --}}
                                @if ($disposisi->status !== 'selesai')
                                    <form action="{{ route('disposisi.selesai', $disposisi->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            Selesai
                                        </button>
                                    </form>
                                @endif

                                {{-- TERUSKAN --}}
                                @if ($disposisi->status !== 'selesai')
                                    <a href="{{ route('disposisi.teruskan', $disposisi->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Teruskan
                                    </a>
                                @endif
                            </td>

                            <td>
                                {{ $disposisi->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada disposisi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
