@extends('layouts.app')

@section('title', 'Riwayat Disposisi')

@section('content')
<div class="container">
    <h4 class="mb-4">📜 Riwayat Disposisi</h4>

    <div class="card mb-3">
        <div class="card-body">
            <strong>No Surat:</strong> {{ $surat->no_surat }} <br>
            <strong>Perihal:</strong> {{ $surat->subject }}
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            @forelse ($disposisis as $index => $item)
                <div class="mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <span class="badge bg-dark">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <strong>
                                {{ $item->dari->nama_karyawan ?? 'Pimpinan' }}
                            </strong>
                            →
                            <strong>
                                {{ $item->ke->nama_karyawan }}
                            </strong>
                            <br>

                            <small class="text-muted">
                                {{ $item->created_at->format('d-m-Y H:i') }}
                            </small>

                            <div class="mt-2">
                                <strong>Instruksi:</strong>
                                <div>{{ $item->instruksi }}</div>
                            </div>

                            <div class="mt-2">
                                <strong>Status:</strong>
                                @if ($item->status == 'baru')
                                    <span class="badge bg-secondary">BARU</span>
                                @elseif ($item->status == 'dibaca')
                                    <span class="badge bg-warning">DIBACA</span>
                                @elseif ($item->status == 'selesai')
                                    <span class="badge bg-success">SELESAI</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            @empty
                <p class="text-center">Belum ada riwayat disposisi.</p>
            @endforelse

        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</div>
@endsection
