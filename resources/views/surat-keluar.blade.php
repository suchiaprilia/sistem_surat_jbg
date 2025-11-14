<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keluar</title>

    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 30px; }
        .container { background: #fff; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        .btn { padding: 6px 10px; background: #3498db; color:white; border-radius: 5px; text-decoration:none; }
        .btn-danger { background: #e74c3c; }
        .alert { padding: 10px; background:#e8ffe8; color:green; margin-top:10px; }
        form { margin-top: 20px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px; }
        h2 { color:#34495e; }
        .form-box { background:#f9f9f9; padding:20px; border-radius:10px; margin-top:20px; }
    </style>
</head>

<body>

<div class="container">

    <h2>📤 Surat Keluar</h2>

    @if(session('success'))
        <p class="alert">{{ session('success') }}</p>
    @endif


    <!-- ======================= FORM TAMBAH / EDIT ======================== -->
    <div class="form-box">

        <h3>{{ isset($editData) ? '✏ Edit Surat Keluar' : '➕ Tambah Surat Keluar' }}</h3>

        <form 
            action="{{ isset($editData) ? route('surat-keluar.update', $editData->id_surat_keluar) : route('surat-keluar.store') }}" 
            method="POST">

            @csrf
            @if(isset($editData))
                @method('PUT')
            @endif

            <label>No Surat</label>
            <input type="text" name="no_surat_keluar" 
                value="{{ $editData->no_surat_keluar ?? '' }}" required>

            <label>Tujuan</label>
            <input type="text" name="destination"
                value="{{ $editData->destination ?? '' }}" required>

            <label>Subject</label>
            <input type="text" name="subject"
                value="{{ $editData->subject ?? '' }}" required>

            <label>Tanggal</label>
            <input type="date" name="date"
                value="{{ $editData->date ?? '' }}" required>

            <label>ID User</label>
            <input type="number" name="id_user"
                value="{{ $editData->id_user ?? '' }}" required>

            <label>ID Number Surat</label>
            <input type="number" name="id_number_surat"
                value="{{ $editData->id_number_surat ?? '' }}" required>

            <button type="submit" class="btn" style="margin-top:10px;">
                {{ isset($editData) ? 'Update' : 'Simpan' }}
            </button>
        </form>

    </div>


    <!-- ======================= TABEL DATA ======================== -->
    <table>
        <tr>
            <th>No</th>
            <th>No Surat</th>
            <th>Tujuan</th>
            <th>Subject</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        @foreach ($suratKeluar as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->no_surat_keluar }}</td>
            <td>{{ $item->destination }}</td>
            <td>{{ $item->subject }}</td>
            <td>{{ $item->date }}</td>
            <td>
                <a class="btn" href="{{ route('surat-keluar.edit', $item->id_surat_keluar) }}">Edit</a>

                <form action="{{ route('surat-keluar.destroy', $item->id_surat_keluar) }}" 
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>
