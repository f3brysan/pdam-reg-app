<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perumdam Lawu Tirta Magetan — Permohonan Ditolak</title>
</head>

<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p>Halo Saudara/i <strong>{{ $permohonan->nama }}</strong>,</p>

    <p>Mohon maaf, permohonan pemasangan sambungan baru Anda <strong>tidak dapat diproses</strong> dan ditolak oleh admin.</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Nomor registrasi</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;"><strong>{{ $permohonan->no_register }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Status permohonan</td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;"><strong>{{ $permohonan->status }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px 0; vertical-align: top;">Catatan</td>
            <td style="padding: 8px 0; text-align: right;">{{ $permohonan->catatan }}</td>
        </tr>
    </table>

    <p style="font-size: 14px; color: #555;">Jika Anda memiliki pertanyaan, silakan hubungi kantor Perumdam Lawu Tirta Magetan.</p>

    <p style="font-size: 13px; color: #888; margin-top: 32px;">Email ini dikirim otomatis, mohon tidak membalas langsung ke alamat ini.</p>
</body>

</html>
