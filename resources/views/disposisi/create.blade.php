@extends('layouts.app')

@section('title', 'Disposisi Surat')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="page-header-title">📌 Disposisi Surat</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('disposisi.store') }}" method="POST">
            @csrf

            <input type="hidden" name="surat_masuk_id" value="{{ $surat->id }}">

            <div class="mb-3">
                <label>No Surat</label>
                <input type="text" class="form-control" value="{{ $surat->no_surat }}" readonly>
            </div>

            <div class="mb-3">
                <label>Perihal</label>
                <input type="text" class="form-control" value="{{ $surat->subject }}" readonly>
            </div>

            <div class="mb-3">
                <label>Tujuan Disposisi</label>
                <select name="ke_karyawan_id" class="form-control" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach ($karyawans as $karyawan)
                        <option value="{{ $karyawan->id_karyawan }}">
                            {{ $karyawan->nama_karyawan }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label>Instruksi</label>
                <textarea name="instruksi" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label>Batas Waktu</label>
                <input type="date" name="batas_waktu" class="form-control">
            </div>

            <div class="text-end">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
