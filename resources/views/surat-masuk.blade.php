<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Surat Masuk</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 40px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #217dbb;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
            background-color: #eaf9ea;
            padding: 8px;
            border-radius: 5px;
            width: fit-content;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📩 Data Surat Masuk</h2>

        <a href="{{ route('surat-masuk.create') }}" class="btn">+ Tambah Surat</a>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <table>
            <tr>
                <th>No</th>
                <th>No Surat</th>
                <th>Dari</th>
                <th>Subject</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            @foreach ($suratMasuk as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->no_surat_masuk }}</td>
                <td>{{ $item->from }}</td>
                <td>{{ $item->subject }}</td>
                <td>{{ $item->date }}</td>
                <td>
                    <a href="{{ route('surat-masuk.edit', $item->id_surat_masuk) }}" class="btn">Edit</a>
                    <form action="{{ route('surat-masuk.destroy', $item->id_surat_masuk) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
