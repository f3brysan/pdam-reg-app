<?php

namespace App\Http\Controllers;

use App\Models\MsMeteran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MsJenisMeteranController extends Controller
{
    public function index(Request $request)
    {
        $jenisMeterans = MsMeteran::all();

        if ($request->ajax()) {
            return DataTables::of($jenisMeterans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-1 edit-btn" data-id="'.$row->id.'">Ubah</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Hapus</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.jenis-meteran.index', compact('jenisMeterans'));
    }

    public function show($id)
    {
        try {
            $jenisMeteran = MsMeteran::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Jenis meteran berhasil ditemukan',
                'data' => $jenisMeteran
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'jenisMeteranName' => 'required|string|max:255',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ], 400);
            }

            $jenisMeteran = MsMeteran::updateOrCreate([
                'id' => $request->id
            ], [
                'nama' => $request->jenisMeteranName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis meteran berhasil disimpan',
                'data' => $jenisMeteran
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $jenisMeteran = MsMeteran::find($request->id);
            $jenisMeteran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis meteran berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
