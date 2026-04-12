<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perumdam Lawu Tirta Magetan — Jadwal pemasangan meter air</title>
</head>

<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p>Halo Saudara/i <strong>{{ ucwords($permohonan->nama) }}</strong>,</p>

    <p>Permohonan Anda telah dijadwalkan untuk pemasangan meter air. Berikut ringkasan informasinya.</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Nomor registrasi</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;"><strong>{{ $permohonan->no_register }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Nomor pelanggan</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;"><strong>{{ $permohonan->no_pelanggan }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Tanggal pemasangan</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;"><strong>{{ \Illuminate\Support\Carbon::parse($permohonanOfficer->tgl_pasang)->format('d/m/Y') }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Petugas</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $permohonanOfficer->petugas->name ?? '—' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Meter</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $permohonanOfficer->msMeteran->nama ?? '—' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Nomor seri</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $permohonanOfficer->nomor_seri }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">Status</td>
            <td style="padding: 8px 0; text-align: right;">{{ $permohonan->status }}</td>
        </tr>
    </table>

    <p style="font-size: 14px; color: #555;">Pastikan Anda atau pihak yang berwenang dapat menerima kedatangan petugas pada tanggal tersebut. Jika ada perubahan, hubungi Perumdam Lawu Tirta Magetan.</p>

    <p style="font-size: 13px; color: #888; margin-top: 32px;">Email ini dikirim otomatis, mohon tidak membalas langsung ke alamat ini.</p>
</body>

</html>
