<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Jabatan</title>

    <style>
        body { font-family: Arial; background:#f4f6f9; padding:30px; }
        .container { background:white; padding:25px; border-radius:10px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th,td { padding:10px; border-bottom:1px solid #ddd; }
        th { background:#3498db; color:white; }
        .btn { padding:6px 12px; background:#3498db; color:white; border-radius:5px; text-decoration:none; }
        .btn-danger { background:#e74c3c; }
        input { width:100%; padding:8px; margin-top:5px; margin-bottom:10px; }
        .form-box { background:#f9f9f9; padding:20px; border-radius:10px; margin-top:20px; }
        .alert { padding:10px; background:#e8ffe8; color:green; margin:10px 0; }
    </style>
</head>

<body>

<div class="container">

    <h2>👔 Data Jabatan</h2>

    <!-- Tombol kembali ke dashboard (yang benar) -->
    <a href="{{ route('dashboard') }}" class="btn">← Kembali ke Dashboard</a>

    @if(session('success'))
        <p class="alert">{{ session('success') }}</p>
    @endif

    <!-- FORM -->
    <div class="form-box">
        <h3>{{ isset($editData) ? '✏ Edit Jabatan' : '➕ Tambah Jabatan' }}</h3>

        <form action="{{ isset($editData) ? route('jabatan.update', $editData->id_jabatan) : route('jabatan.store') }}" method="POST">
            @csrf
            @if(isset($editData))
                @method('PUT')
            @endif

            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" value="{{ $editData->nama_jabatan ?? '' }}" required>

            <button class="btn" type="submit">
                {{ isset($editData) ? 'Update' : 'Simpan' }}
            </button>
        </form>
    </div>

    <!-- TABEL -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama Jabatan</th>
            <th>Aksi</th>
        </tr>

        @foreach ($jabatans as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_jabatan }}</td>
            <td>
                <a class="btn" href="{{ route('jabatan.edit', $item->id_jabatan) }}">Edit</a>

                <form action="{{ route('jabatan.destroy', $item->id_jabatan) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus jabatan?')" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>
