@extends('layouts.app')

@section('content')
<div class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h2 style="margin:0;">Agenda</h2>

        <div>
            <a href="{{ url('/agenda?range=today') }}">Hari ini</a> |
            <a href="{{ url('/agenda?range=week') }}">Minggu ini</a> |
            <a href="{{ url('/agenda?range=month') }}">Bulan ini</a> |
            <a href="{{ url('/agenda') }}">Semua</a>
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div style="padding:10px; background:#e6ffed; border:1px solid #b7ebc6; border-radius:6px; margin-bottom:10px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Alert error --}}
    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; border-radius:6px; margin-bottom:10px;">
            <b>⚠️ Ada error:</b>
            <ul style="margin:0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah agenda --}}
    <div style="border:1px solid #ddd; padding:15px; border-radius:8px; margin-bottom:20px;">
        <h4 style="margin-top:0;">Tambah Agenda</h4>

        <form method="POST" action="{{ url('/agenda') }}">
            @csrf

            <div style="margin-bottom:10px;">
                <label>Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="display:flex; gap:10px; margin-bottom:10px;">
                <div style="flex:1;">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                           style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
                </div>

                <div style="flex:1;">
                    <label>Tanggal Selesai (opsional)</label>
                    <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                           style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
                </div>
            </div>

            <div style="margin-bottom:10px;">
                <label>Lokasi (opsional)</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="margin-bottom:10px;">
                <label>Keterangan (opsional)</label>
                <textarea name="keterangan" rows="3"
                          style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">{{ old('keterangan') }}</textarea>
            </div>

            <div style="margin-bottom:10px;">
                <label>Status</label>
                <select name="status" style="padding:8px; border:1px solid #ccc; border-radius:6px;">
                    <option value="terjadwal" {{ old('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="ditunda" {{ old('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                    <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            {{-- kalau mau relasi ke surat masuk / keluar bisa aktifkan --}}
            {{--
            <div style="display:flex; gap:10px; margin-bottom:10px;">
                <div style="flex:1;">
                    <label>ID Surat Masuk (opsional)</label>
                    <input type="number" name="surat_masuk_id" style="width:100%; padding:8px;">
                </div>
                <div style="flex:1;">
                    <label>ID Surat Keluar (opsional)</label>
                    <input type="number" name="surat_keluar_id" style="width:100%; padding:8px;">
                </div>
            </div>
            --}}

            <button type="submit"
                    style="padding:8px 14px; background:#2d8cff; color:white; border:none; border-radius:6px;">
                + Simpan Agenda
            </button>
        </form>
    </div>

    {{-- List agenda --}}
    <div style="border:1px solid #ddd; padding:15px; border-radius:8px;">
        <h4 style="margin-top:0;">Daftar Agenda</h4>

        <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;">
            <thead style="background:#f5f5f5;">
                <tr>
                    <th>Judul</th>
                    <th>Mulai</th>
                    <th>Status</th>
                    <th style="width:240px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($agendas as $a)
                    <tr>
                        <td>{{ $a->judul }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($a->tanggal_mulai)->format('d M Y H:i') }}
                        </td>
                        <td>{{ $a->status }}</td>
                        <td>
                            {{-- tombol selesai --}}
                            @if($a->status !== 'selesai')
                                <form method="POST" action="{{ url('/agenda/'.$a->id.'/done') }}" style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                            style="padding:6px 10px; border:none; border-radius:6px; background:#28a745; color:white;">
                                        ✔ Selesai
                                    </button>
                                </form>
                            @endif

                            {{-- tombol hapus --}}
                            <form method="POST" action="{{ url('/agenda/'.$a->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin hapus agenda ini?')"
                                        style="padding:6px 10px; border:none; border-radius:6px; background:#dc3545; color:white;">
                                    🗑 Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada agenda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $agendas->links() }}
        </div>
    </div>

</div>
@endsection
