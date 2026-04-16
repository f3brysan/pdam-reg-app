<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasangan</title>
    <style>
        * { box-sizing: border-box; }
        @page { size: A4 landscape; margin: 10mm; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8.5pt;
            color: #000;
        }
        .title {
            text-align: center;
            font-weight: 700;
            font-size: 14pt;
            margin-bottom: 8px;
        }
        .meta {
            font-size: 9pt;
            margin-bottom: 10px;
        }
        .meta div { margin: 2px 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #444;
            padding: 5px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }
        th {
            background: #f2f2f2;
            font-weight: 700;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="title">Laporan Pemasangan</div>

    <div class="meta">
        <div><strong>Filter kecamatan</strong>: {{ $filter['kecamatan'] ?: 'Semua' }}</div>
        <div><strong>Rentang tgl permohonan</strong>:
            {{ $filter['from'] ?: '—' }} s/d {{ $filter['to'] ?: '—' }}
        </div>
        <div><strong>Total data</strong>: {{ number_format($data->count(), 0, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 26px;">No</th>
                <th class="text-center" style="width: 70px;">Tgl permohonan</th>
                <th class="text-center" style="width: 80px;">Merk WM</th>
                <th class="text-center" style="width: 78px;">Nomor WM</th>
                <th class="text-center" style="width: 80px;">No. pelanggan</th>
                <th class="text-center" style="width: 120px;">Nama pemohon</th>
                <th class="text-center">Alamat</th>
                <th class="text-center" style="width: 105px;">Kecamatan</th>
                <th class="text-center" style="width: 110px;">Petugas</th>
                <th class="text-center" style="width: 90px;">Nominal</th>
                <th class="text-center" style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="text-right">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if ($row->tgl_daftar)
                            {{ \Carbon\Carbon::parse($row->tgl_daftar)->locale('id')->translatedFormat('d-m-Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ optional($row->msMeteran)->nama ?? '—' }}</td>
                    <td>{{ $row->nomor_seri ?? '—' }}</td>
                    <td>{{ $row->no_pelanggan ?? '—' }}</td>
                    <td>{{ $row->nama ?? '—' }}</td>
                    <td>{{ $row->alamat ?? '—' }}</td>
                    <td>{{ $row->kecamatan ?? '—' }}</td>
                    <td>{{ optional(optional($row->permohonanOfficer)->petugas)->name ?? '—' }}</td>
                    <td class="text-right">
                        Rp.&nbsp;{{ number_format(optional($row->permohonanBiling)->price ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-center">{{ $row->status ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

