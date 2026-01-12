<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Surat</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .sub {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            font-size: 11px;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>Rekap Surat</h2>
<div class="sub">
    Dicetak pada: {{ date('d-m-Y H:i') }}
</div>

<h3>Surat Masuk</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Surat</th>
            <th>Pengirim</th>
            <th>Perihal</th>
            <th>Jenis</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratMasuk as $i => $s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->no_surat }}</td>
            <td>{{ $s->pengirim }}</td>
            <td>{{ $s->subject }}</td>
            <td>{{ optional($s->jenisSurat)->jenis_surat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Surat Keluar</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Surat</th>
            <th>Tujuan</th>
            <th>Perihal</th>
            <th>Jenis</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratKeluar as $i => $s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->no_surat_keluar }}</td>
            <td>{{ $s->destination }}</td>
            <td>{{ $s->subject }}</td>
            <td>{{ optional($s->jenisSurat)->jenis_surat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
