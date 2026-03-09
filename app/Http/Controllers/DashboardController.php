<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('dashboard.admin.index');
    }

    public function userIndex()
    {
        return view('dashboard.user.index');
    }
}
