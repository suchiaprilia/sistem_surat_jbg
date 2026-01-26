@extends('layouts.app')

@section('title', 'Teruskan Disposisi')

@section('content')
<div class="container">
    <h4 class="mb-4">🔁 Teruskan Disposisi</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf

                {{-- 🔑 INI KUNCI UTAMA --}}
                <input type="hidden" name="disposisi_lama_id" value="{{ $disposisi->id }}">
                <input type="hidden" name="surat_masuk_id" value="{{ $disposisi->surat_masuk_id }}">

                <div class="mb-3">
                    <label>No Surat</label>
                    <input type="text" class="form-control"
                           value="{{ $disposisi->suratMasuk->no_surat }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Instruksi Sebelumnya</label>
                    <textarea class="form-control" rows="2" readonly>
{{ $disposisi->instruksi }}
                    </textarea>
                </div>

                <div class="mb-3">
                    <label>Teruskan ke</label>
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
                    <label>Instruksi Baru</label>
                    <textarea name="instruksi" class="form-control" rows="3" required>
Mohon ditindaklanjuti
                    </textarea>
                </div>

                <div class="mb-3">
                    <label>Batas Waktu</label>
                    <input type="date" name="batas_waktu" class="form-control">
                </div>

                <div class="text-end">
                    <a href="{{ route('disposisi.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Teruskan Disposisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
