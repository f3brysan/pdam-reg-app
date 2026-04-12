<?php

namespace App\Http\Controllers;

class FrontpageController extends Controller
{
    public function index()
    {
        return view('front.index', [
            'stats' => [
                'pelanggan_aktif' => 45_280,
                'sambungan_baru_tahun_ini' => 1_240,
                'wilayah_layanan' => 18,
            ],
            'jam_kerja' => [
                ['hari' => 'Senin – Jumat', 'jam' => '08.00 – 15.00 WIB', 'keterangan' => 'Layanan kasir & informasi'],
                ['hari' => 'Sabtu', 'jam' => '08.00 – 12.00 WIB', 'keterangan' => 'Hanya layanan informasi & pembayaran'],
                ['hari' => 'Minggu & libur nasional', 'jam' => 'Tutup', 'keterangan' => 'Pembayaran online tetap dapat dilakukan melalui channel resmi'],
            ],
            'slides' => [
                [
                    'judul' => 'Air Bersih untuk Magetan',
                    'teks' => 'Melayani distribusi air minum berkualitas di seluruh wilayah Kabupaten Magetan.',
                    'gradient' => 'linear-gradient(135deg, #7dd3fc 0%, #0891b2 100%)',
                ],
                [
                    'judul' => 'Pasang Baru & Perpanjangan',
                    'teks' => 'Ajukan permohonan sambungan baru secara online — proses lebih rapi dan transparan.',
                    'gradient' => 'linear-gradient(135deg, #22d3ee 0%, #2563eb 100%)',
                ],
                [
                    'judul' => 'Hemat Air, Hemat Energi',
                    'teks' => 'Laporkan kebocoran dan rawat instalasi rumah Anda. Setiap tetes air berharga.',
                    'gradient' => 'linear-gradient(135deg, #38bdf8 0%, #0d9488 100%)',
                ],
            ],
        ]);
    }
}
