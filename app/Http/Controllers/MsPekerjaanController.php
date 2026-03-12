<?php

namespace App\Http\Controllers;

use App\Models\MsPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MsPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        $pekerjaans = MsPekerjaan::all();

        if ($request->ajax()) {
            return DataTables::of($pekerjaans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-1 edit-btn" data-id="'.$row->id.'">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.pekerjaan.index', compact('pekerjaans'));
    }

    public function show($id)
    {
        try {
            $pekerjaan = MsPekerjaan::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil ditemukan',
                'data' => $pekerjaan
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
                'pekerjaanName' => 'required|string|max:255',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ], 400);
            }

            $pekerjaan = MsPekerjaan::updateOrCreate([
                'id' => $request->id
            ], [
                'nama' => $request->pekerjaanName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil disimpan',
                'data' => $pekerjaan
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
            $pekerjaan = MsPekerjaan::find($request->id);
            $pekerjaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
