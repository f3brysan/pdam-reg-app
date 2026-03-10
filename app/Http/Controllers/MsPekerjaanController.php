<?php

namespace App\Http\Controllers;

use App\Models\MsPekerjaan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MsPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        $pekerjaans = MsPekerjaan::all();

        if ($request->ajax()) {
            return DataTables::of($pekerjaans)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-1 edit-btn" data-id="'.$row->id.'">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.pekerjaan.index', compact('pekerjaans'));
    }
}
