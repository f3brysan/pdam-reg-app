<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        ->with('roles')
        ->limit(10)
        ->get();
        return view('dashboard.admin.index', compact('onlineUsers'));
    }

    public function userIndex()
    {
        return view('dashboard.user.index');
    }
}
