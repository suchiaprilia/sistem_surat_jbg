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
        a, button {
            text-decoration: none;
            cursor: pointer;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s;
            border: none;
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
        .form-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 50%;
            margin: 20px auto;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📩 Data Surat Masuk</h2>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <button class="btn" id="btnTambah">+ Tambah Surat</button>

        <div id="formTambah" class="form-box hidden">
            <h3 id="formTitle">Tambah Surat Masuk</h3>
            <form id="suratForm" action="{{ route('surat-masuk.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id_surat_masuk" id="id_surat_masuk">

                <input type="text" name="no_surat_masuk" id="no_surat_masuk" placeholder="Nomor Surat" required>
                <input type="text" name="from" id="from" placeholder="Dari" required>
                <input type="email" name="tujuan_email" id="tujuan_email" placeholder="Tujuan Email" required>
                <input type="text" name="subject" id="subject" placeholder="Subjek" required>
                <input type="text" name="received_by" id="received_by" placeholder="Diterima oleh" required>
                <input type="number" name="id_user" id="id_user" placeholder="ID User" required>
                <input type="date" name="date" id="date" required>

                <button type="submit" class="btn">Simpan</button>
                <button type="button" class="btn btn-danger" id="btnBatal">Batal</button>
            </form>
        </div>

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
                    <button 
                        class="btn btn-edit"
                        data-id="{{ $item->id_surat_masuk }}"
                        data-no="{{ $item->no_surat_masuk }}"
                        data-from="{{ $item->from }}"
                        data-email="{{ $item->tujuan_email }}"
                        data-subject="{{ $item->subject }}"
                        data-received="{{ $item->received_by }}"
                        data-user="{{ $item->id_user }}"
                        data-date="{{ $item->date }}">
                        Edit
                    </button>
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

    <script>
        const btnTambah = document.getElementById('btnTambah');
        const btnBatal = document.getElementById('btnBatal');
        const formBox = document.getElementById('formTambah');
        const formTitle = document.getElementById('formTitle');
        const form = document.getElementById('suratForm');
        const method = document.getElementById('formMethod');

        // tombol tambah
        btnTambah.onclick = () => {
            formBox.classList.remove('hidden');
            formTitle.textContent = 'Tambah Surat Masuk';
            form.action = "{{ route('surat-masuk.store') }}";
            method.value = 'POST';
            form.reset();
        };

        // tombol batal
        btnBatal.onclick = () => {
            formBox.classList.add('hidden');
        };

        // tombol edit
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = () => {
                formBox.classList.remove('hidden');
                formTitle.textContent = 'Edit Surat Masuk';
                form.action = "/surat-masuk/" + btn.dataset.id;
                method.value = 'PUT';

                document.getElementById('id_surat_masuk').value = btn.dataset.id;
                document.getElementById('no_surat_masuk').value = btn.dataset.no;
                document.getElementById('from').value = btn.dataset.from;
                document.getElementById('tujuan_email').value = btn.dataset.email;
                document.getElementById('subject').value = btn.dataset.subject;
                document.getElementById('received_by').value = btn.dataset.received;
                document.getElementById('id_user').value = btn.dataset.user;
                document.getElementById('date').value = btn.dataset.date;
            };
        });
    </script>
</body>
</html>
