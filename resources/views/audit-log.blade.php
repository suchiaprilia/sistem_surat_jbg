@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item">Audit Log</li>
                </ul>
            </div>
            <div class="col-md-12 mt-3">
                <h2 class="page-header-title">🧾 Audit Log</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form class="row g-2" method="GET" action="{{ route('audit-log.index') }}">
            <div class="col-md-3">
                <select name="modul" class="form-control">
                    <option value="">-- Filter Modul --</option>
                    <option value="surat_masuk" {{ request('modul')=='surat_masuk'?'selected':'' }}>Surat Masuk</option>
                    <option value="surat_keluar" {{ request('modul')=='surat_keluar'?'selected':'' }}>Surat Keluar</option>
                    <option value="agenda" {{ request('modul')=='agenda'?'selected':'' }}>Agenda</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="aksi" class="form-control">
                    <option value="">-- Filter Aksi --</option>
                    <option value="create" {{ request('aksi')=='create'?'selected':'' }}>Create</option>
                    <option value="update" {{ request('aksi')=='update'?'selected':'' }}>Update</option>
                    <option value="delete" {{ request('aksi')=='delete'?'selected':'' }}>Delete</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Filter</button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-light w-100" href="{{ route('audit-log.index') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Data ID</th>
                        <th>Keterangan</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->created_at ? $log->created_at->format('d-m-Y H:i') : '-' }}</td>
                            <td>{{ $log->keterangan_user ?? '-' }}</td>
                            <td>{{ $log->modul }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $log->aksi }}</span>
                            </td>
                            <td>{{ $log->data_id ?? '-' }}</td>
                            <td>{{ $log->keterangan ?? '-' }}</td>
                            <td>{{ $log->ip ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-3 text-muted">Belum ada audit log</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $logs->links() }}
    </div>
</div>
@endsection
