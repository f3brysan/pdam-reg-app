<?php

namespace App\Http\Controllers;

use App\Models\MsJenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class MsJenisDokumenController extends Controller
{
    public function index(Request $request)
    {
        $jenisDokumens = MsJenisDokumen::all();

        if ($request->ajax()) {
            return DataTables::of($jenisDokumens)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-1 edit-btn" data-id="'.$row->id.'">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.jenis-dokumen.index', compact('jenisDokumens'));
    }

    public function show($id)
    {
        try {
            $jenisDokumen = MsJenisDokumen::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Jenis dokumen berhasil ditemukan',
                'data' => $jenisDokumen,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'jenisDokumenName' => 'required|string|max:255',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first(),
                ], 400);
            }

            $jenisDokumen = MsJenisDokumen::updateOrCreate([
                'id' => $request->id,
            ], [
                'nama' => $request->jenisDokumenName,
                'slug' => Str::slug($request->jenisDokumenName, '_'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis dokumen berhasil disimpan',
                'data' => $jenisDokumen,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $jenisDokumen = MsJenisDokumen::find($request->id);
            $jenisDokumen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis dokumen berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
