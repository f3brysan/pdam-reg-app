<?php

namespace App\Http\Controllers;

use App\Models\PermohonanTransaction;
use Illuminate\Support\Facades\Crypt;
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
}
