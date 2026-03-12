<?php

namespace App\Http\Controllers;

use App\Models\MsJenisTempatTinggal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MsJenisTempatTinggalController extends Controller
{
    public function index(Request $request)
    {
        $jenisTempatTinggals = MsJenisTempatTinggal::all();

        if ($request->ajax()) {
            return DataTables::of($jenisTempatTinggals)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm mr-1 edit-btn" data-id="'.$row->id.'">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.jenis-tempat-tinggal.index', compact('jenisTempatTinggals'));
    }

    public function show($id)
    {
        try {
            $jenisTempatTinggal = MsJenisTempatTinggal::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Jenis tempat tinggal berhasil ditemukan',
                'data' => $jenisTempatTinggal
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
                'jenisTempatTinggalName' => 'required|string|max:255',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ], 400);
            }

            $jenisTempatTinggal = MsJenisTempatTinggal::updateOrCreate([
                'id' => $request->id
            ], [
                'nama' => $request->jenisTempatTinggalName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis tempat tinggal berhasil disimpan',
                'data' => $jenisTempatTinggal
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
            $jenisTempatTinggal = MsJenisTempatTinggal::find($request->id);
            $jenisTempatTinggal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis tempat tinggal berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
