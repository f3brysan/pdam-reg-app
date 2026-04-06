<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Permohonan Langganan Air - PERUMDAM LAWU TIRTA</title>
    <style>
        /* Reset total untuk cetak dan DOMPDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Times New Roman', 'Helvetica Neue', Arial, sans-serif;
            background: #fff;
            padding: 0.6cm 0.8cm;
            font-size: 9pt;
            color: #000;
            line-height: 1.3;
        }

        /* Container utama - ukuran pas 1 halaman A4/F4 */
        .form {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Kop daerah */
        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        .kop .daerah {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #000;
        }

        .kop .perumdam {
            font-size: 11pt;
            font-weight: 600;
            margin-top: 2px;
        }

        /* Judul permohonan */
        .judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 12px 0 16px 0;
            letter-spacing: 1px;
        }

        /* Tabel utama form - style border rapi seperti formulir asli */
        .tabel-form {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .tabel-form tr {
            border-bottom: 1px solid #aaa;
        }

        .tabel-form td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .tabel-form td:first-child {
            width: 38%;
            font-weight: 600;
        }

        .tabel-form td:last-child {
            width: 62%;
            border-left: 1px dotted #999;
            padding-left: 12px;
        }

        /* Gaya untuk pilihan centang (seperti di gambar) */
        .pilihan-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 18px;
            align-items: center;
        }

        .pilihan-item {
            white-space: nowrap;
        }

        .cek-simbol {
            font-weight: bold;
            font-size: 11pt;
        }

        .garis-bawah {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 100px;
            margin-left: 4px;
        }

        /* PERBAIKAN UTAMA: Tanda Tangan dengan tabel untuk kompatibilitas DOMPDF */
        .ttd-wrapper {
            width: 100%;
            margin-top: 28px;
            margin-bottom: 12px;
        }
        
        /* Menggunakan tabel untuk layout TTD (paling aman untuk DOMPDF) */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .ttd-table td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }
        
        .ttd-table .ttd-left-cell {
            text-align: left;
        }
        
        .ttd-table .ttd-right-cell {
            text-align: left;
        }
        
        .ttd-line {
            border-top: 1px solid #000;
            width: 85%;
            margin-top: 5px;
            margin-bottom: 4px;
        }
        
        .ttd-label {
            font-weight: bold;
            margin-top: 4px;
        }
        
        .ttd-name {
            margin-top: 2px;
        }
        
        /* Atau bisa juga pakai float (alternatif jika tabel tidak cocok) */
        /* .ttd-container-float {
            overflow: auto;
            width: 100%;
        }
        .ttd-left-float {
            float: left;
            width: 45%;
        }
        .ttd-right-float {
            float: right;
            width: 45%;
        } */

        /* Teks kecil catatan */
        .catatan-bawah {
            font-size: 9pt;
            margin-top: 16px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        /* Pemberitahuan (Emberthuan) */
        .pemberitahuan {
            border-left: 5px solid #b45f1b;
            background: #fff7e8;
            padding: 8px 12px;
            margin: 16px 0 12px 0;
            font-size: 9.8pt;
        }

        .pemberitahuan p {
            margin: 3px 0;
        }

        .pemberitahuan .title-notice {
            font-weight: bold;
            font-size: 10.5pt;
        }

        /* Supaya semua muat 1 halaman */
        @media print {
            body {
                padding: 0.3cm;
                margin: 0;
            }
            .form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form">
        <!-- KOP persis seperti gambar -->
        <div class="kop">
            <div class="daerah">PERUSAHAAN DAERAH AIR MINUM<br>MAGETAN</div>
            <div class="perumdam">PERUMDAM LAWU TIRTA KABUPATEN MAGETAN</div>
        </div>

        <!-- Judul permohonan -->
        <div class="judul">PERMOHONAN MENJADI LANGGANAN</div>

        <!-- Tabel form sesuai poin a sampai j (mirip asli) -->
        <table class="tabel-form">
            <tr>
                <td>a. Register</td>
                <td>: {{ $data->no_register ?? '-' }}</td>
            </tr>
            <tr>
                <td>b. NIK</td>
                <td>: {{ $data->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>c. Nama Lengkap</td>
                <td>: {{ $data->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>d. Alamat</td>
                <td>: {{ $data->alamat ?? '-' }}, No. {{ $data->nomor_rumah ?? '-' }}, Kel. {{ $data->kelurahan ?? '-' }}, Kec. {{ $data->kecamatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>e. Pekerjaan</td>
                <td>: {{ optional($data->msPekerjaan)->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>f. Jumlah Keluarga</td>
                <td>: {{ $data->jumlah_keluarga ?? '-' }} orang</td>
            </tr>
            <tr>
                <td>g. Apakah Rumah ini digunakan untuk Perusahaan, Kantor, Tempat Tinggal, Asrama, Penginapan, Pabrik, Rumah Makan, Hotel, Rumah Sakit, Rumah Ibadah, Panti Asuhan, Lain-lain......</td>
                <td>
                    <div class="pilihan-group">
                        <span class="cek-simbol">✔</span> {{ optional($data->msJenisTempatTinggal)->nama ?? 'Tempat Tinggal' }}
                    </div>
                 </td>
            </tr>
            <tr>
                <td>h. Berapa banyak Kran-Kran yang di pasang pada rumah (termasuk Dougche/Pembersih Toilet)</td>
                <td>: {{ $data->jumlah_kran ?? '-' }} unit kran</td>
            </tr>
            <tr>
                <td>i. Bersediakah membayar biaya Sambungan / Rekening Air Minimum di Kantor PERUMDAM LAWU TIRTA Kab. Magetan</td>
                <td>: <span class="cek-simbol">✔</span> Bersedia</td>
            </tr>
            <tr>
                <td>j. Diisi Oleh Petugas PERUMDAM LAWU TIRTA Kab. Magetan</td>
                <td>: (lihat tabel data pemasangan di bawah)</td>
            </tr>
        </table>

        <!-- Tabel data petugas (format tabel seperti data lain) -->
        <table class="tabel-form" style="margin-top: 8px;">
            <tr>
                <td style="width: 38%; font-weight: 600;">Nomer Pelanggan</td>
                <td style="width: 62%; border-left: 1px dotted #999; padding-left: 12px;">: {{ $data->no_pelanggan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 38%; font-weight: 600;">Meter Air dipasang oleh</td>
                <td style="width: 62%; border-left: 1px dotted #999; padding-left: 12px;">: {{ optional(optional($data->permohonanOfficer)->petugas)->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 38%; font-weight: 600;">Tanggal Pemasangan</td>
                <td style="width: 62%; border-left: 1px dotted #999; padding-left: 12px;">: {{ !empty(optional($data->permohonanOfficer)->tgl_pasang) ? \Carbon\Carbon::parse(optional($data->permohonanOfficer)->tgl_pasang)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td style="width: 38%; font-weight: 600;">Merek Water Meter</td>
                <td style="width: 62%; border-left: 1px dotted #999; padding-left: 12px;">: {{ optional(optional($data->permohonanOfficer)->msMeteran)->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 38%; font-weight: 600;">Nomor seri</td>
                <td style="width: 62%; border-left: 1px dotted #999; padding-left: 12px;">: {{ optional($data->permohonanOfficer)->nomor_seri ?? ($data->no_seri_meteran ?? '-') }}</td>
            </tr>
        </table>       

        <!-- PERBAIKAN: Tanda tangan menggunakan tabel (kompatibel DOMPDF) -->
        <div class="ttd-wrapper">
            <table class="ttd-table">
                <tr>
                    <td class="ttd-left-cell">
                        Magetan, {{ !empty($data->tgl_daftar) ? \Carbon\Carbon::parse($data->tgl_daftar)->locale('id')->translatedFormat('d F Y') : '-' }}<br>
                        <div style="margin-top:5px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}" alt="QR Code" width="50" height="50">
                        </div>
                        <div class="ttd-line">                        
                        </div>
                        <div class="ttd-label">(Pemohon)</div>
                        <div class="ttd-name">{{ $data->nama ?? '-' }}</div>
                    </td>
                    <td class="ttd-right-cell">
                        Mengetahui :<br>
                        <div style="margin-top:5px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($data->no_register ?? 'permohonan') }}" alt="QR Code" width="50" height="50">
                        </div>
                        <div class="ttd-line"></div>
                        <div class="ttd-label">Petugas PERUMDAM</div>
                        <div class="ttd-name">{{ optional(optional($data->permohonanOfficer)->petugas)->name ?? '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- ALTERNATIF jika masih ingin pakai flex/float (uncomment jika tabel tidak cocok) -->
        <!-- 
        <div class="ttd-container-float">
            <div class="ttd-left-float">
                Magetan, 12 Januari 2026<br>
                <div class="ttd-line"></div>
                <div class="ttd-label">(Pemohon)</div>
                <div class="ttd-name">SUPRIYANTO</div>
            </div>
            <div class="ttd-right-float">
                Mengetahui :<br>
                <div class="ttd-line"></div>
                <div class="ttd-label">Kepala Cabang IX Ngariboyo</div>
                <div class="ttd-name">AGUS WAHYUDI</div>
            </div>
        </div>
        <div style="clear:both"></div>
        -->

        <!-- Keterangan tambahan (mirip asli) -->
        <div class="catatan-bawah">
            {{-- * Diharap membayar biaya Sambungan Air pada Kas PERUMDAM LAWU TIRTA<br> --}}
            Formulir ini sah sebagai permohonan menjadi langganan.
        </div>
    </div>
</body>
</html>