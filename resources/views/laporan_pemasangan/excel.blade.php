<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td colspan="11"><strong>Laporan Pemasangan</strong></td>
        </tr>
        <tr>
            <td colspan="11">
                <strong>Filter kecamatan</strong>: {{ $filter['kecamatan'] ?: 'Semua' }}
                &nbsp;|&nbsp;
                <strong>Rentang tgl permohonan</strong>: {{ $filter['from'] ?: '—' }} s/d {{ $filter['to'] ?: '—' }}
                &nbsp;|&nbsp;
                <strong>Total</strong>: {{ $data->count() }}
            </td>
        </tr>
        <tr>
            <th>No</th>
            <th>Tgl permohonan</th>
            <th>Merk WM</th>
            <th>Nomor WM</th>
            <th>No. pelanggan</th>
            <th>Nama pemohon</th>
            <th>Alamat</th>
            <th>Kecamatan</th>
            <th>Petugas</th>
            <th>Nominal</th>
            <th>Status</th>
        </tr>
        @foreach ($data as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($row->tgl_daftar)
                        {{ \Carbon\Carbon::parse($row->tgl_daftar)->format('d-m-Y') }}
                    @endif
                </td>
                <td>{{ !empty($row->permohonanOfficer) ? $row->permohonanOfficer->msMeteran->nama : '—' }}</td>
                <td>{{ !empty($row->permohonanOfficer) ? $row->permohonanOfficer->nomor_seri : '—' }}</td>
                <td>{{ $row->no_pelanggan ?? '' }}</td>
                <td>{{ $row->nama ?? '' }}</td>
                <td>{{ $row->alamat ?? '' }}</td>
                <td>{{ $row->kecamatan ?? '' }}</td>
                <td>{{ optional(optional($row->permohonanOfficer)->petugas)->name ?? '' }}</td>
                <td>{{ number_format(optional($row->permohonanBiling)->price ?? 0, 0, ',', '.') }}</td>
                <td>{{ $row->status ?? '' }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>

