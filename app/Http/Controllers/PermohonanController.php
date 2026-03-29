<?php

namespace App\Http\Controllers;

use App\Models\MsJenisDokumen;
use App\Models\MsJenisTempatTinggal;
use App\Models\MsPekerjaan;
use App\Models\PermohonanBiling;
use App\Models\PermohonanDokumenTransaction;
use App\Models\PermohonanTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermohonanController extends Controller
{
    public function index()
    {
        $permohonans = PermohonanTransaction::with(['msPekerjaan', 'msJenisTempatTinggal'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('permohonan.admin.index', compact('permohonans'));
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
                'jenis_tempat_tinggal' => 'required',
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

            $userId = auth()->user()->id;
            $insertData = [
                'id' => $userId,
                'no_register' => $noRegister,
                'tgl_daftar' => $request->tgl_daftar,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'ms_jenis_tempat_tinggal_id' => $request->jenis_tempat_tinggal,
                'ms_pekerjaan_id' => $request->pekerjaan,
                'jumlah_kran' => $request->jumlah_kran,
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
                    'permohonan_transaction_id' => $userId,
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

    public function show($id)
    {
        $id = Crypt::decryptString($id);
        $permohonan = PermohonanTransaction::with(['msPekerjaan', 'msJenisTempatTinggal'])
            ->where('id', $id)
            ->first();

        $permohonanBiling = PermohonanBiling::where('id', $id)->first();

        $permohonanDokumen = PermohonanDokumenTransaction::with(['msJenisDokumen'])
            ->where('permohonan_transaction_id', $id)
            ->get();

        return view('permohonan.admin.show', compact('permohonan', 'permohonanDokumen', 'permohonanBiling'));
    }

    public function validasi(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);
            $permohonan = PermohonanTransaction::where('id', $id)->first();
            $checkBilling = PermohonanBiling::where('id', $id)->first();

            if ($checkBilling) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permohonan sudah divalidasi',
                ], 422);
            }

            $createVA = rand(1000, 9999).$permohonan->no_register;

            DB::beginTransaction();

            $createBilling = PermohonanBiling::create([
                'id' => $id,
                'no_va' => $createVA,
                'path' => null,
                'is_valid' => false,
            ]);

            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'status' => 'MENUNGGU PEMBAYARAN',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil divalidasi',
                'data' => $createBilling
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);

            $validate = Validator::make($request->all(), [
                'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'validation_error',
                    'field' => 'bukti_pembayaran',
                    'message' => $validate->errors()->first(),
                    'all_errors' => $validate->errors()
                ], 422);
            }

            $file = $request->file('bukti_pembayaran');
            $path = $file->store('uploads/bukti_pembayaran', 'public');

            DB::beginTransaction();
            $updateBilling = PermohonanBiling::where('id', $id)->update([
                'path' => $path,
            ]);

            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'status' => 'MENUNGGU VERIFIKASI PEMBAYARAN',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah',
                'data' => $updateBilling
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);
            $permohonan = PermohonanTransaction::where('id', $id)->first();
            $checkBilling = PermohonanBiling::where('id', $id)->first();

            if (! $checkBilling) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permohonan tidak ditemukan',
                ], 422);
            }

            DB::beginTransaction();
            $updateBilling = PermohonanBiling::where('id', $id)->update([
                'is_valid' => true,
            ]);

            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'status' => 'MENUNGGU JADWAL PEMASANGAN',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil diverifikasi',
                'data' => $updateStatus
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
