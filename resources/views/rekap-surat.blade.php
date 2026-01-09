<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Surat</title>
    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .container-rekap {
            padding: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* FILTER */
        .filter-row {
            display: flex;
            gap: 16px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-row label {
            font-size: 13px;
            color: #555;
            display: block;
            margin-bottom: 4px;
        }

        .filter-row input {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background: #4f8cff;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #3a78f2;
        }

        .btn-link {
            margin-left: 10px;
            color: #4f8cff;
            text-decoration: none;
            font-size: 14px;
        }

        /* SUMMARY */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card {
            border-radius: 14px;
            padding: 18px;
            color: #fff;
        }

        .summary-card p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .summary-card h3 {
            font-size: 28px;
            margin: 6px 0 0;
        }

        .blue { background: linear-gradient(135deg, #5aa9ff, #3f7cff); }
        .green { background: linear-gradient(135deg, #5ee7b2, #2ecc71); }
        .orange { background: linear-gradient(135deg, #ffbe76, #f0932b); }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f1f3f9;
            padding: 12px;
            font-size: 14px;
            text-align: left;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        h4 {
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<div class="container-rekap">

    <h2 class="page-title">Rekap Surat</h2>

    <!-- FILTER -->
    <div class="card">
        <form method="GET">
            <div class="filter-row">
                <div>
                    <label>Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label>Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div>
                    <button class="btn-primary">Filter</button>
                    <a href="/rekap-surat" class="btn-link">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- RINGKASAN -->
    <div class="summary-grid">
        <div class="summary-card blue">
            <p>Total Surat Masuk</p>
            <h3>{{ $totalMasuk }}</h3>
        </div>
        <div class="summary-card green">
            <p>Total Surat Keluar</p>
            <h3>{{ $totalKeluar }}</h3>
        </div>
        <div class="summary-card orange">
            <p>Total Semua Surat</p>
            <h3>{{ $totalMasuk + $totalKeluar }}</h3>
        </div>
    </div>

    <!-- SURAT MASUK -->
    <div class="card">
        <h4>Surat Masuk</h4>
        <table>
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Pengirim</th>
                    <th>Perihal</th>
                    <th>Jenis</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suratMasuk as $s)
                <tr>
                    <td>{{ $s->no_surat }}</td>
                    <td>{{ $s->pengirim }}</td>
                    <td>{{ $s->subject }}</td>
                    <td>{{ $s->jenisSurat->jenis_surat ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- SURAT KELUAR -->
    <div class="card">
        <h4>Surat Keluar</h4>
        <table>
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Tujuan</th>
                    <th>Perihal</th>
                    <th>Jenis</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suratKeluar as $s)
                <tr>
                    <td>{{ $s->no_surat_keluar }}</td>
                    <td>{{ $s->destination }}</td>
                    <td>{{ $s->subject }}</td>
                    <td>{{ $s->jenisSurat->jenis_surat ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
