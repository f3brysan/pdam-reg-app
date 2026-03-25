<?php

namespace App\Http\Controllers;

use App\Models\PermohonanTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        ->with('roles')
        ->limit(10)
        ->get();
        return view('dashboard.admin.index', compact('onlineUsers'));
    }

    public function userIndex()
    {
        $permohonanTransactions = PermohonanTransaction::where('id', Auth::user()->id)->with('permohonanDokumenTransactions')->first(); 

        
        return view('dashboard.user.index', compact('permohonanTransactions'));
    }
}
