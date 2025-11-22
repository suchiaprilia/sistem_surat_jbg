<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>

    <style>
        body { font-family: Arial; background:#f4f6f9; padding:30px; }
        .container { background:white; padding:25px; border-radius:10px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th,td { padding:10px; border-bottom:1px solid #ddd; }
        th { background:#3498db; color:white; }
        .btn { padding:6px 12px; background:#3498db; color:white; border-radius:5px; text-decoration:none; }
        .btn-danger { background:#e74c3c; }
        input, select { width:100%; padding:8px; margin-top:5px; margin-bottom:10px; }
        .form-box { background:#f9f9f9; padding:20px; border-radius:10px; margin-top:20px; }
        .alert { padding:10px; background:#e8ffe8; color:green; margin:10px 0; }
        .error { color:red; font-size:13px; margin-bottom:10px; }
    </style>
</head>

<body>

<div class="container">

    <h2>👨‍💼 Data Karyawan</h2>

    <a href="{{ route('dashboard') }}" class="btn">← Kembali ke Dashboard</a>

    @if(session('success'))
        <p class="alert">{{ session('success') }}</p>
    @endif

    <div class="form-box">
        <h3>{{ isset($editData) ? '✏ Edit Karyawan' : '➕ Tambah Karyawan' }}</h3>

        <form action="{{ isset($editData) ? route('karyawan.update', $editData->id_karyawan) : route('karyawan.store') }}" method="POST">
            @csrf
            @if(isset($editData)) @method('PUT') @endif

            <label>Nama Karyawan</label>
            <input type="text" name="nama_karyawan"
                   value="{{ old('nama_karyawan', $editData->nama_karyawan ?? '') }}"
                   pattern="[A-Za-z\s]+"
                   title="Nama hanya boleh huruf."
                   required>
            @error('nama_karyawan')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Email</label>
            <input type="email" name="email_karyawan"
                   value="{{ old('email_karyawan', $editData->email_karyawan ?? '') }}"
                   required>
            @error('email_karyawan')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Divisi</label>
            <select name="id_divisi" required>
                <option value="">— Pilih Divisi —</option>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}"
                        {{ old('id_divisi', $editData->id_divisi ?? '') == $d->id_divisi ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                @endforeach
            </select>

            <label>Jabatan</label>
            <select name="id_jabatan" required>
                <option value="">— Pilih Jabatan —</option>
                @foreach($jabatan as $j)
                    <option value="{{ $j->id_jabatan }}"
                        {{ old('id_jabatan', $editData->id_jabatan ?? '') == $j->id_jabatan ? 'selected' : '' }}>
                        {{ $j->nama_jabatan }}
                    </option>
                @endforeach
            </select>

            <button class="btn" type="submit">
                {{ isset($editData) ? 'Update' : 'Simpan' }}
            </button>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Divisi</th>
            <th>Jabatan</th>
            <th>Aksi</th>
        </tr>

        @foreach ($karyawans as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_karyawan }}</td>
            <td>{{ $item->email_karyawan }}</td>
            <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
            <td>{{ $item->jabatan->nama_jabatan ?? '-' }}</td>

            <td>
                <a class="btn" href="{{ route('karyawan.edit', $item->id_karyawan) }}">Edit</a>

                <form action="{{ route('karyawan.destroy', $item->id_karyawan) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus data?')" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>

</div>

</body>
</html>
