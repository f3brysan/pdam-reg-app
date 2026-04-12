<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>BA Pemasangan Sambungan Baru - Final Rapi</title>
    <style>
        /* Reset & base styling - optimal untuk Dompdf & cetak */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: white;
            margin: 0;
            padding: 24px 28px;
            font-size: 9pt;
            line-height: 1.45;
            color: #000;
        }

        /* container utama rapi, border tipis, tidak pecah halaman */
        .container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #aaa;
            padding: 20px 30px 30px 30px;
            box-shadow: none;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* judul dan nomor */
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            letter-spacing: 1px;
        }

        .nomor {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin: -3px 0 22px 0;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }

        /* preambule (hari, tanggal) */
        .preambule {
            margin-bottom: 18px;
            text-align: justify;
            line-height: 1.5;
        }

        .preambule p {
            margin-bottom: 6px;
        }

        /* gaya untuk pihak kesatu & kedua */
        .pihak {
            margin: 12px 0 12px 0;
            width: 100%;
        }

        .pihak-item {
            margin-bottom: 3px;
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
        }

        .pihak-label {
            font-weight: bold;
            min-width: 160px;
            display: inline-block;
            vertical-align: top;
        }

        .pihak-detail {
            flex: 1;
            font-weight: normal;
        }

        /* dasar hukum (surat perintah kerja) */
        .dasar-hukum {
            margin: 16px 0 16px 0;
            font-style: normal;
            line-height: 1.45;
            background: #fafaf5;
            padding: 8px 12px;
            border-left: 4px solid #2c3e66;
        }

        /* tabel data pemasangan - rapi & proporsional */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 5px 0;
            font-size: 9pt;
            background: #fff;
        }

        .data-table td,
        .data-table th {
            border: 1px solid #111;
            padding: 10px 12px;
            vertical-align: top;
        }

        .data-table th {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }

        .data-table td:first-child {
            font-weight: bold;
            width: 34%;
            background-color: #f8f9fc;
        }

        .data-table td:last-child {
            font-weight: normal;
            background-color: #ffffff;
        }

        /* paragraf keterangan selesai */
        .keterangan {
            text-align: justify;
            margin: 12px 0 12px 0;
            line-height: 1.5;
        }

        /* area tanda tangan 2 kiri + 2 kanan (aman untuk DOMPDF) */
        .ttd-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .ttd-grid td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 12px 16px 8px 16px;
        }

        .ttd-space {
            height: 56px;
        }

        .sign-name {
            display: inline-block;
            min-width: 180px;
            border-top: 1px dotted #000;
            padding-top: 6px;
            font-weight: bold;
        }

        .ttd-subtitle {
            margin-top: 4px;
        }

        .ttd-divider {
            border-top: 1px solid #ccc;
        }

        /* spasi garis nama */
        .garis-nama-tebal {
            font-weight: bold;
            margin-top: 8px;
        }

        /* tambahan rapi untuk selaras */
        .text-upper {
            text-transform: uppercase;
        }

        .bold {
            font-weight: bold;
        }

        .italic-note {
            font-style: normal;
        }

        /* penyesuaian cetak & dompdf */
        @media print {
            body {
                margin: 0;
                padding: 0.2in;
            }

            .container {
                border: none;
                padding: 10px 15px;
            }

            .page-break {
                page-break-after: avoid;
                break-inside: avoid;
            }
        }

        .avoid-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        hr {
            margin: 8px 0;
            border: 0;
            border-top: 0.5px solid #bbb;
        }

        /* alignment tanggal & tempat seragam */
        .tempat-tanggal {
            font-weight: normal;
            margin-bottom: 5px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container avoid-break">
        @php
            $tglDaftar = ! empty($data->tgl_daftar) ? \Carbon\Carbon::parse($data->tgl_daftar) : null;
            $tglPasang = ! empty(optional($data->permohonanOfficer)->tgl_pasang) ? \Carbon\Carbon::parse(optional($data->permohonanOfficer)->tgl_pasang) : null;
            $tanggalAcara = $tglPasang ?: $tglDaftar;
            $namaPetugas = optional(optional($data->permohonanOfficer)->petugas)->name ?? '-';
            $namaPelanggan = $data->nama ?? '-';
            $alamatLengkap = trim(($data->alamat ?? '-').', '.$data->kelurahan.', '.$data->kecamatan);
            $nomorSeri = optional($data->permohonanOfficer)->nomor_seri ?? ($data->no_seri_meteran ?? '-');
            $meteran = optional(optional($data->permohonanOfficer)->msMeteran)->nama ?? optional($data->msMeteran)->nama ?? '-';
        @endphp

        <!-- judul utama & nomor rapi -->
        <div class="title">BERITA ACARA PEMASANGAN SAMBUNGAN BARU</div>
        <div class="nomor">NOMOR : {{ $data->no_register ?? '-' }} / BA / IX</div>

        <!-- hari dan tanggal dengan format jelas -->
        <div class="preambule">
            <p>
                Pada hari
                <strong>{{ ($tanggalAcara ? $tanggalAcara->locale('id')->translatedFormat('l') : '-') }}</strong>,
                tanggal
                <strong>{{ ucwords($data->terbilangTanggal) }}</strong>, bulan
                <strong>{{ $tanggalAcara ? $tanggalAcara->locale('id')->translatedFormat('F') : '-' }}</strong>, tahun
                <strong>{{ ucwords($data->terbilangTahun) }}</strong>,
                kami yang bertanda tangan di bawah ini :
            </p>
        </div>

        <!-- Pihak kesatu -->
        <div class="pihak">
            <div class="pihak-item">
                <span class="pihak-label">I. Nama Pelaksana :</span>
                <span class="pihak-detail"><strong>{{ strtoupper($namaPetugas) }}</strong></span>
            </div>
            <div class="pihak-item">
                <span class="pihak-label">Jabatan :</span>
                <span class="pihak-detail">Petugas Pemasang Sambungan Baru</span>
            </div>
            <div class="pihak-item">
                <span class="pihak-label">Selanjutnya disebut</span>
                <span class="pihak-detail"><strong>Pihak Kesatu.</strong></span>
            </div>
        </div>

        <!-- Pihak kedua -->
        <div class="pihak">
            <div class="pihak-item">
                <span class="pihak-label">II. Nama Pelanggan :</span>
                <span class="pihak-detail"><strong>{{ strtoupper($namaPelanggan) }}</strong></span>
            </div>
            <div class="pihak-item">
                <span class="pihak-label">Alamat :</span>
                <span class="pihak-detail">{{ strtoupper($alamatLengkap) }}</span>
            </div>
            <div class="pihak-item">
                <span class="pihak-label">Selanjutnya disebut</span>
                <span class="pihak-detail"><strong>Pihak Kedua.</strong></span>
            </div>
        </div>

        <!-- Dasar Surat Perintah Kerja -->
        <div class="dasar-hukum">
            Berdasarkan Surat Perintah Kerja Kepala Cabang IX Ngariboyo, Nomor : {{ $data->no_register ?? '-' }} / SPK /
            IX<br>
            Tanggal {{ $tanggalAcara ? $tanggalAcara->locale('id')->translatedFormat('d F Y') : '-' }}
        </div>

        <div class="italic-note" style="margin: 5px 0 0px 0;">
            Pihak Kesatu telah mengadakan Pemasangan Sambungan Rumah dan Penyetelan Water Meter Untuk :
        </div>

        <!-- tabel data water meter dan pelanggan (rapi border) -->
        <table class="data-table">
            <tr>
                <td>No. Saluran</td>
                <td><strong>{{ $data->no_pelanggan ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>Nama Pelanggan</td>
                <td><strong>{{ strtoupper($namaPelanggan) }}</strong></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><strong>{{ strtoupper($alamatLengkap) }}</strong></td>
            </tr>
            <tr>
                <td>Merk Water Meter (WM)</td>
                <td><strong>{{ strtoupper($meteran) }}</strong></td>
            </tr>
            <tr>
                <td>No. Seri WM</td>
                <td><strong>{{ strtoupper($nomorSeri) }}</strong></td>
            </tr>
            <tr>
                <td>Angka WM Pasang</td>
                <td><strong>0 (Nol)</strong></td>
            </tr>
        </table>

        <!-- keterangan penyelesaian pemasangan -->
        <div class="keterangan">
            Pemasangan Sambungan Baru di atas telah selesai kami laksanakan dibuktikan Berita Acara ini telah
            ditandatangani oleh kedua belah pihak.
        </div>

        <div style="margin: 5px 0 10px 0;">
            Demikian Berita Acara ini kami buat untuk dipergunakan sebagaimana mestinya.
        </div>

        <!-- Tempat dan Tanggal dibuat seragam, lalu tanda tangan Pihak I & Pihak II lebih rapi -->
        <div style="text-align: right; margin: 8px 0 5px 0; font-size: 9pt;">
            Magetan, {{ $tanggalAcara ? $tanggalAcara->locale('id')->translatedFormat('d F Y') : '-' }}
        </div>

        <!-- Tanda tangan: 2 kiri dan 2 kanan -->
        <table class="ttd-grid">
            <tr>
                <td>
                    <p><strong>Pihak Kesatu</strong><br>Pelaksana Pemasangan</p>
                    <div class="ttd-space">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}"
                            alt="QR Code" width="50" height="50">
                    </div>
                    <div class="sign-name">( <u>{{ strtoupper($namaPetugas) }}</u> )</div>
                    <p class="ttd-subtitle">Nama terang: {{ $namaPetugas }}</p>
                </td>
                <td>
                    <p><strong>Pihak Kedua</strong><br>Pelanggan</p>
                    <div class="ttd-space">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}"
                            alt="QR Code" width="50" height="50">
                    </div>
                    <div class="sign-name">( <u>{{ strtoupper($namaPelanggan) }}</u> )</div>
                    <p class="ttd-subtitle">Nama terang: {{ $namaPelanggan }}</p>
                </td>
            </tr>
            <tr>
                <td class="ttd-divider">
                    <p><strong>Mengesahkan,</strong><br>Kepala Cabang IX Ngariboyo</p>
                    <div class="ttd-space">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}"
                            alt="QR Code" width="50" height="50">
                    </div>
                    <p><strong>AGUS WAHYUDI</strong></p>
                    <p>NPP. 96 71 174</p>
                </td>
                <td class="ttd-divider">
                    <p><strong>Dibuat Oleh,</strong><br>Kasi Teknik IX Ngariboyo</p>
                    <div class="ttd-space">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}"
                            alt="QR Code" width="50" height="50">
                    </div>
                    <p><strong>{{ strtoupper($namaPetugas) }}</strong></p>
                    <p>Petugas Pemasang</p>
                </td>
            </tr>
        </table>

        <!-- informasi tambahan kecil untuk memastikan layout proporsional (tanpa mengganggu) -->
        <div style="height: 4px;"></div>
        <hr style="margin-top: 10px;">
        <div style="font-size: 9pt; text-align: center; margin-top: 8px; color: #444;">
            Dokumen ini sah dan berlaku sebagai bukti pemasangan sambungan baru.
        </div>
    </div>
</body>

</html>