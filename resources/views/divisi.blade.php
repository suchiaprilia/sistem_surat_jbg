<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Divisi</title>

    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 30px; }
        .container { background: #fff; padding: 25px; border-radius: 10px; }
        h2 { color: #34495e; }
        .btn { padding: 8px 12px; background: #3498db; color:white; border-radius:5px; text-decoration:none; }
        .btn-danger { background: #e74c3c; }
        .btn-green { background: #27ae60; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color:white; }
        tr:hover { background: #f0f0f0; }
        .alert { padding: 10px; background:#e8ffe8; color:green; margin-top:10px; border-radius:5px; }
        form { margin-top: 20px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border-radius:5px; border:1px solid #ccc; }
        .form-box { background:#f9f9f9; padding:20px; border-radius:10px; margin-top:20px; }
    </style>
</head>

<body>

<div class="container">

    <h2>🏢 Data Divisi</h2>

    <!-- Tombol kembali ke dashboard -->
    <a href="{{ route('dashboard') }}" class="btn btn-green">← Kembali ke Dashboard</a>

    @if(session('success'))
        <p class="alert">{{ session('success') }}</p>
    @endif

    
    <!-- =========== FORM TAMBAH / EDIT =========== -->
    <div class="form-box">
        <h3>{{ isset($editData) ? '✏ Edit Divisi' : '➕ Tambah Divisi' }}</h3>

        <form 
            action="{{ isset($editData) ? route('divisi.update', $editData->id_divisi) : route('divisi.store') }}" 
            method="POST">
            
            @csrf
            @if(isset($editData))
                @method('PUT')
            @endif

            <label>Nama Divisi</label>
            <input type="text" name="nama_divisi" 
                   value="{{ $editData->nama_divisi ?? '' }}" required>

            <label>Kode Divisi</label>
            <input type="text" name="kode_divisi" 
                   value="{{ $editData->kode_divisi ?? '' }}">

            <label>Kepala Divisi</label>
            <input type="text" name="kepala_divisi"
                   value="{{ $editData->kepala_divisi ?? '' }}">

            <label>Kontak</label>
            <input type="text" name="kontak"
                   value="{{ $editData->kontak ?? '' }}">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3">{{ $editData->deskripsi ?? '' }}</textarea>

            <button type="submit" class="btn" style="margin-top:10px;">
                {{ isset($editData) ? 'Update' : 'Simpan' }}
            </button>
        </form>
    </div>


    <!-- =========== TABEL DATA =========== -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama Divisi</th>
            <th>Kode</th>
            <th>Kepala Divisi</th>
            <th>Kontak</th>
            <th>Aksi</th>
        </tr>

        @foreach ($divisis as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_divisi }}</td>
            <td>{{ $item->kode_divisi }}</td>
            <td>{{ $item->kepala_divisi }}</td>
            <td>{{ $item->kontak }}</td>

            <td>
                <a class="btn" href="{{ route('divisi.edit', $item->id_divisi) }}">Edit</a>

                <form action="{{ route('divisi.destroy', $item->id_divisi) }}" 
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Hapus divisi ini?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>
