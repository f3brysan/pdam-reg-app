<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PermohonanController extends Model
{
    //
    public function index()
    {
        return view('permohonan.index');
    }

    public function create()
    {
        $msJenisTempatTinggal = MsJenisTempatTinggal::all();
        $msPekerjaan = MsPekerjaan::all();
        return view('permohonan.user.create', compact('msJenisTempatTinggal', 'msPekerjaan'));
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'nama' => 'required',
                'nik' => 'required',
                'alamat' => 'required',
                'telepon' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json(['success' => false, 'message' => $validate->errors()->first()], 422);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
