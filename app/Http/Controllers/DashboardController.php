<?php

namespace App\Http\Controllers;

use App\Models\PermohonanOfficer;
use App\Models\PermohonanTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->roles->first()->name;
        switch ($role) {
            case 'admin':
                return $this->adminIndex();
                break;
            case 'user':
                return $this->userIndex();
                break;
            case 'teknisi':
                return $this->teknisiIndex();
                break;
            case 'pimpinan':
                return $this->pimpinanIndex();
                break;
        }
    }

    public function adminIndex()
    {
        $onlineUsers = User::whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(5)->timestamp);
        })
        ->get();
        
        
        $permohonanTransactions = PermohonanTransaction::where('status', 'DIAJUKAN')
            ->orderBy('created_at', 'desc')            
            ->get();
        
        return view('dashboard.admin.index', compact('onlineUsers', 'permohonanTransactions'));
    }

    public function userIndex()
    {
        $permohonanTransactions = PermohonanTransaction::with('permohonanBiling', 'permohonanOfficer')->where('id', Auth::user()->id)->first();

        $dokumenPendukung = [];
        if ($permohonanTransactions) {
            $dokumenPendukung = DB::table('ms_jenis_dokumens')
                ->leftJoin('permohonan_dokumen_transactions', 'ms_jenis_dokumens.id', '=', 'permohonan_dokumen_transactions.ms_jenis_dokumen_id')
                ->where('permohonan_dokumen_transactions.permohonan_transaction_id', $permohonanTransactions->id)
                ->get();
        }

        return view('dashboard.user.index', compact('permohonanTransactions', 'dokumenPendukung'));
    }

    public function teknisiIndex()
    {
        $teknisiId = Auth::id();

        $baseQuery = PermohonanOfficer::with(['permohonanTransaction', 'msMeteran'])
            ->where('petugas_id', $teknisiId);

        $totalPemasangan = (clone $baseQuery)->count();
        $totalSelesai = (clone $baseQuery)->where('is_done', true)->count();
        $totalAntrian = (clone $baseQuery)->where('is_done', false)->count();
        $jadwalHariIni = (clone $baseQuery)->whereDate('tgl_pasang', now()->toDateString())->count();
        $jadwalTerdekat = (clone $baseQuery)
            ->where('is_done', false)
            ->whereDate('tgl_pasang', '>=', now()->toDateString())
            ->orderBy('tgl_pasang', 'asc')
            ->first();

        $daftarPemasangan = (clone $baseQuery)
            ->orderByRaw('CASE WHEN is_done = 0 THEN 0 ELSE 1 END')
            ->orderBy('tgl_pasang', 'asc')
            ->get();

        return view('dashboard.teknisi.index', compact(
            'totalPemasangan',
            'totalSelesai',
            'totalAntrian',
            'jadwalHariIni',
            'jadwalTerdekat',
            'daftarPemasangan'
        ));
    }

    public function pimpinanIndex()
    {
        return view('dashboard.pimpinan.index');
    }
}
