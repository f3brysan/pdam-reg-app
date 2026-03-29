<?php

namespace App\Http\Controllers;

use App\Models\MsJenisDokumen;
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
        $permohonanTransactions = PermohonanTransaction::with('permohonanBiling')->where('id', Auth::user()->id)->first();

        $dokumenPendukung = [];
        if ($permohonanTransactions) {
            $dokumenPendukung = DB::table('ms_jenis_dokumens')
                ->leftJoin('permohonan_dokumen_transactions', 'ms_jenis_dokumens.id', '=', 'permohonan_dokumen_transactions.ms_jenis_dokumen_id')
                ->where('permohonan_dokumen_transactions.permohonan_transaction_id', $permohonanTransactions->id)
                ->get();
        }

        return view('dashboard.user.index', compact('permohonanTransactions', 'dokumenPendukung'));
    }
}
