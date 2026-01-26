@extends('layouts.app')

@section('title', 'Disposisi Saya')

@section('content')
<div class="container">
    <h4 class="mb-4">📥 Disposisi Saya</h4>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Perihal</th>
                        <th>Instruksi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposisis as $disposisi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $disposisi->suratMasuk->no_surat ?? '-' }}</td>
                            <td>{{ $disposisi->suratMasuk->subject ?? '-' }}</td>
                            <td>{{ $disposisi->instruksi }}</td>
                            <td>{{ $disposisi->batas_waktu ?? '-' }}</td>

                            {{-- STATUS --}}
                            <td>
                                @if ($disposisi->status == 'baru')
                                    <span class="badge bg-secondary">BARU</span>
                                @elseif ($disposisi->status == 'dibaca')
                                    <span class="badge bg-warning">DIBACA</span>
                                @elseif ($disposisi->status == 'selesai')
                                    <span class="badge bg-success">SELESAI</span>
                                @else
                                    <span class="badge bg-dark">UNKNOWN</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                {{-- Dibaca --}}
                                @if ($disposisi->status == 'baru')
                                    <form action="{{ route('disposisi.dibaca', $disposisi->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info mb-1">
                                            Dibaca
                                        </button>
                                    </form>
                                @endif

                                {{-- Selesai --}}
                                @if ($disposisi->status != 'selesai')
                                    <form action="{{ route('disposisi.selesai', $disposisi->id) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success mb-1">
                                            Selesai
                                        </button>
                                    </form>
                                @endif

                                {{-- TERUSKAN DISPOSISI --}}
                                @if ($disposisi->status != 'selesai')
                                    <a href="{{ route('disposisi.teruskan', $disposisi->id) }}"
                                       class="btn btn-sm btn-warning mb-1">
                                        Teruskan
                                    </a>
                                @endif
                            </td>

                            <td>{{ $disposisi->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
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
