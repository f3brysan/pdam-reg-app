<?php

namespace App\Http\Controllers;

use App\Models\PermohonanTransaction;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Riskihajar\Terbilang\Facades\Terbilang;
use Spatie\LaravelPdf\Facades\Pdf;

class BerkasPDFController extends Controller
{
    public function pdfPermohonan($id)
    {
        $id = Crypt::decrypt($id);

        $data = PermohonanTransaction::with([
            'msPekerjaan',
            'msJenisTempatTinggal',
            'permohonanOfficer.petugas',
            'permohonanOfficer.msMeteran',
        ])->find($id);
        
        return Pdf::view('pdf.permohonan_psb', ['data' => $data])
            ->format('a4')
            ->name('permohonan-'.$data->no_register.'.pdf');
        
    }

    public function pdfBeritaAcara($id)
    {
        $id = Crypt::decrypt($id);

        $data = PermohonanTransaction::with([
            'msPekerjaan',
            'msJenisTempatTinggal',
            'permohonanOfficer.petugas',
            'permohonanOfficer.msMeteran',
        ])->find($id);

        $tglDaftar = !empty($data->tgl_daftar) ? \Carbon\Carbon::parse($data->tgl_daftar) : null;
        $tglPasang = !empty(optional($data->permohonanOfficer)->tgl_pasang) ? \Carbon\Carbon::parse(optional($data->permohonanOfficer)->tgl_pasang) : null;
        $tanggalAcara = $tglPasang ?: $tglDaftar;

        $tglAcara = intval($tanggalAcara->locale('id')->translatedFormat('d'));
        $tahunAcara = intval($tanggalAcara->locale('id')->translatedFormat('Y'));

        

        Config::set('terbilang.locale', 'id');
        $data->terbilangTanggal = Terbilang::make($tglAcara);
        $data->terbilangTahun = Terbilang::make($tahunAcara);        
        
        return Pdf::view('pdf.berita_acara', ['data' => $data])
            ->format('a4')
            ->name('berita-acara-'.$data->no_register.'.pdf');
    }
}
