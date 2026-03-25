<?php

namespace App\Http\Controllers;

use App\Models\MsJenisDokumen;
use App\Models\MsJenisTempatTinggal;
use App\Models\MsPekerjaan;
use App\Models\PermohonanDokumenTransaction;
use App\Models\PermohonanTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermohonanController extends Controller
{
    public function index()
    {
        return view('permohonan.index');
    }

    public function create()
    {
        $msJenisTempatTinggal = MsJenisTempatTinggal::all();
        $msPekerjaan = MsPekerjaan::all();
        $msJenisDokumen = MsJenisDokumen::all();
        return view('permohonan.user.create', compact('msJenisTempatTinggal', 'msPekerjaan', 'msJenisDokumen'));
    }

    public function store(Request $request)
    {
        try {

            $validate = Validator::make($request->all(), [
                'nama' => 'required',
                'nik' => 'required',
                'alamat' => 'required',
                'telepon' => 'required',
                'jenis-tempat-tinggal' => 'required',
                'pekerjaan' => 'required',
                'jumlah_kran' => 'required',
                'tgl_daftar' => 'required',
                'nomor_rumah' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $msJenisDokumen = MsJenisDokumen::all();

            foreach ($msJenisDokumen as $item) {
                $slug = $item->slug;
                if (! $request->hasFile($slug)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus mengupload dokumen '.$item->nama,
                    ], 422);
                }
            }

            if (empty($request->konfirmasi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus menyetujui persyaratan konfirmasi',
                    'error_type' => 'validation_error',
                    'field' => 'konfirmasi',
                ], 422);
            }

            if ($validate->fails()) {
                // Custom error return
                $errors = $validate->errors();
                $firstField = $errors->keys()[0] ?? null;
                $firstMessage = $errors->first();
                return response()->json([
                    'success' => false,
                    'error_type' => 'validation_error',
                    'field' => $firstField,
                    'message' => $firstMessage,
                    'all_errors' => $errors
                ], 422);
            }

            $checkSquence = PermohonanTransaction::where('no_register', 'like', date('Ymd').'%')->orderBy('no_register', 'desc')->first();

            if ($checkSquence) {
                $noRegister = $checkSquence->no_register + 1;
            } else {
                $noRegister = date('Ymd').'0001';
            }

            DB::beginTransaction();

            $insertData = [
                'no_register' => $noRegister,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'jenis_tempat_tinggal' => $request->jenis_tempat_tinggal,
                'pekerjaan' => $request->pekerjaan,
                'jumlah_kran' => $request->jumlah_kran,
                'tgl_daftar' => $request->tgl_daftar,
                'nomor_rumah' => $request->nomor_rumah,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'DIAJUKAN',
            ];

            $permohonanTransaction = PermohonanTransaction::create($insertData);

            foreach ($msJenisDokumen as $item) {

                if ($request->hasFile($item->slug)) {
                    $file = $request->file($item->slug);
                    $path = $file->store('uploads/'.$item->slug, 'public');
                    $size = $file->getSize();
                }

                $permohonanDokumenTransaction = PermohonanDokumenTransaction::create([
                    'permohonan_transaction_id' => $permohonanTransaction->id,
                    'ms_jenis_dokumen_id' => $item->id,
                    'path' => $path,
                    'size' => $size,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil diajukan',
                'data' => $permohonanTransaction
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
