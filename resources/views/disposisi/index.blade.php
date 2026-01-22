@extends('layouts.app')

@section('title', 'Disposisi Saya')

@section('content')
<div class="container">
    <h4 class="mb-4">📥 Disposisi Saya</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Perihal</th>
                        <th>Instruksi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
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
                            <td>
                                <span class="badge bg-primary">
                                    {{ strtoupper($disposisi->status) }}
                                </span>
                            </td>
                            <td>{{ $disposisi->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
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
