<?php

namespace App\Http\Controllers;

use App\Models\PermohonanTransaction;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class LaporanPemasanganController extends Controller
{
    public function index(Request $request)
    {
        $kecamatanList = PermohonanTransaction::query()
            ->whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $permohonanTransactions = $this->buildFilteredQuery($request)
            ->with(
                'permohonanOfficer.petugas',
                'permohonanOfficer.msMeteran',
                'msPekerjaan',
                'msJenisTempatTinggal',
                'user',
                'permohonanBiling'
            )
            ->get();            

        return view('laporan_pemasangan.index', [
            'permohonanTransactions' => $permohonanTransactions,
            'kecamatanList' => $kecamatanList,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $kecamatan = $request->input('kecamatan');
        $from = $request->input('tgl_permohonan_from');
        $to = $request->input('tgl_permohonan_to');

        $data = $this->buildFilteredQuery($request)
            ->with(
                'permohonanOfficer.petugas',
                'permohonanOfficer.msMeteran',
                'permohonanBiling'
            )
            ->orderBy('tgl_daftar', 'asc')
            ->get();

        $namaFile = 'laporan-pemasangan'
            . (!empty($kecamatan) ? '-kec-' . preg_replace('/[^a-z0-9\-]+/i', '-', $kecamatan) : '')
            . (!empty($from) ? '-from-' . $from : '')
            . (!empty($to) ? '-to-' . $to : '')
            . '.pdf';

        return Pdf::view('laporan_pemasangan.pdf', [
            'data' => $data,
            'filter' => [
                'kecamatan' => $kecamatan,
                'from' => $from,
                'to' => $to,
            ],
        ])
            ->format('a4')
            ->name($namaFile);
    }

    public function exportExcel(Request $request)
    {
        $kecamatan = $request->input('kecamatan');
        $from = $request->input('tgl_permohonan_from');
        $to = $request->input('tgl_permohonan_to');

        $rows = $this->buildFilteredQuery($request)
            ->with('permohonanOfficer.petugas', 'permohonanOfficer.msMeteran')
            ->orderBy('tgl_daftar', 'asc')
            ->get();

        $namaFile = 'laporan-pemasangan'
            . (!empty($kecamatan) ? '-kec-' . preg_replace('/[^a-z0-9\-]+/i', '-', $kecamatan) : '')
            . (!empty($from) ? '-from-' . $from : '')
            . (!empty($to) ? '-to-' . $to : '')
            . '-' . date('YmdHis')
            . '.xls';

        $html = view('laporan_pemasangan.excel', [
            'data' => $rows,
            'filter' => [
                'kecamatan' => $kecamatan,
                'from' => $from,
                'to' => $to,
            ],
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ]);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = PermohonanTransaction::query();

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->input('kecamatan'));
        }

        $from = $request->input('tgl_permohonan_from');
        $to = $request->input('tgl_permohonan_to');
        if (!empty($from) || !empty($to)) {
            if (!empty($from) && !empty($to)) {
                $query->whereBetween('tgl_daftar', [$from, $to]);
            } elseif (!empty($from)) {
                $query->whereDate('tgl_daftar', '>=', $from);
            } elseif (!empty($to)) {
                $query->whereDate('tgl_daftar', '<=', $to);
            }
        }

        return $query;
    }
}
