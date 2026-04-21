<?php

namespace App\Http\Controllers;

use App\Mail\PemohonanBillingMail;
use App\Mail\PemohonanPemasanganMail;
use App\Models\MsJenisDokumen;
use App\Models\MsJenisTempatTinggal;
use App\Models\MsMeteran;
use App\Models\MsPekerjaan;
use App\Models\OfficerDocument;
use App\Models\PermohonanBiling;
use App\Models\PermohonanDokumenTransaction;
use App\Models\PermohonanOfficer;
use App\Models\PermohonanTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'nik.required' => 'NIK wajib diisi.',
                'alamat.required' => 'Alamat wajib diisi.',
                'telepon.required' => 'Nomor telepon wajib diisi.',
                'jenis_tempat_tinggal.required' => 'Jenis tempat tinggal wajib dipilih.',
                'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
                'jumlah_kran.required' => 'Jumlah kran wajib diisi.',
                'tgl_daftar.required' => 'Tanggal daftar wajib diisi.',
                'kecamatan.required' => 'Kecamatan wajib dipilih.',
                'kelurahan.required' => 'Kelurahan wajib dipilih.',
                'latitude.required' => 'Titik lokasi pada peta (lintang) wajib ditentukan.',
                'longitude.required' => 'Titik lokasi pada peta (bujur) wajib ditentukan.',
            ]);

            $msJenisDokumen = MsJenisDokumen::all();

            foreach ($msJenisDokumen as $item) {
                $slug = $item->slug;
                if (! $request->hasFile($slug)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus mengunggah dokumen '.$item->nama.'.',
                        'error_type' => 'validation_error',
                        'field' => $slug,
                    ], 422);
                }

                $fileValidator = Validator::make(
                    [$slug => $request->file($slug)],
                    [$slug => ['file', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg,pdf']],
                    [
                        $slug.'.file' => 'Berkas dokumen '.$item->nama.' tidak valid.',
                        $slug.'.mimes' => 'Dokumen '.$item->nama.' hanya boleh berformat gambar (JPEG, PNG, GIF, WebP, BMP, SVG) atau PDF.',
                    ]
                );

                if ($fileValidator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $fileValidator->errors()->first($slug),
                        'error_type' => 'validation_error',
                        'field' => $slug,
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
                    'all_errors' => $errors,
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
                'data' => $permohonanTransaction,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);

        $permohonan = PermohonanTransaction::with(['msPekerjaan', 'msJenisTempatTinggal'])
            ->where('id', $id)
            ->first();

        $permohonanBiling = PermohonanBiling::with('validBy')->where('id', $id)->first();

        $permohonanOfficer = PermohonanOfficer::with('petugas')->where('id', $id)->first();

        $officers = User::role('teknisi')->select('id', 'name')->get();

        $msMeteran = MsMeteran::select('id', 'nama')->get();

        $permohonanDokumen = PermohonanDokumenTransaction::with(['msJenisDokumen'])
            ->where('permohonan_transaction_id', $id)
            ->get();

        $officerDocuments = OfficerDocument::where('permohonan_transaction_id', $id)->where('petugas_id', auth()->user()->id)->get();

        if (Auth::user()->roles->first()->name == 'teknisi') {
            return view('permohonan.teknisi.show', compact('permohonan', 'permohonanDokumen', 'permohonanBiling', 'permohonanOfficer', 'officers', 'msMeteran', 'officerDocuments'));
        }

        return view('permohonan.admin.show', compact('permohonan', 'permohonanDokumen', 'permohonanBiling', 'permohonanOfficer', 'officers', 'msMeteran', 'officerDocuments'));
    }

    public function validasi(Request $request, $id)
    {
        try {

            $id = Crypt::decrypt($id);
            $harga = $request->harga;
            $harga = str_replace('.', '', $harga);
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
                'price' => $harga,
                'is_valid' => false,
            ]);

            $checkNoPelanggan = PermohonanTransaction::where('no_pelanggan', $request->no_pelanggan)->first();

            if ($checkNoPelanggan) {
                return response()->json([
                    'success' => false,
                    'message' => 'No. pelanggan sudah digunakan',
                ], 422);
            }

            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'no_pelanggan' => $request->no_pelanggan,
                'status' => 'MENUNGGU PEMBAYARAN',
            ]);

            DB::commit();

            $permohonan->refresh();

            $pemohon = User::find($id);
            
            // Pastikan nomor WA diawali 62
            $no_wa = $permohonan->telepon;            
            
            // Hilangkan whitespace dan strip
            $no_wa = preg_replace('/[\s\-]/', '', $no_wa);
            if (substr($no_wa, 0, 2) !== '62') {
                // Jika angka pertama 0 ubah ke 62, jika tidak tambahkan 62 di depan
                if (substr($no_wa, 0, 1) === '0') {
                    $no_wa = '62' . substr($no_wa, 1);
                } else {
                    $no_wa = '62' . $no_wa;
                }
            }

           $message = "Halo Saudara/i $permohonan->nama,\n\n"
            . "Permohonan Anda telah divalidasi.\n"
            . "Silakan melakukan pembayaran sesuai rincian berikut:\n\n"
            . "Nomor Registrasi: ".$permohonan->no_register."\n"
            . "Nomor Pelanggan: ".$permohonan->no_pelanggan."\n"
            . "Nomor VA: ".$createBilling->no_va."\n"
            . "Jumlah Tagihan: Rp. ".$createBilling->price."\n"
            . "Status Permohonan: MENUNGGU PEMBAYARAN\n\n"
            . "Lakukan pembayaran melalui mitra yang disediakan Perumdam Lawu Tirta Magetan menggunakan nomor VA di atas.";
            
            $kirimWa = $this->kirimWa($no_wa, $message);            

            if ($pemohon && filter_var($pemohon->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($pemohon->email)->send(new PemohonanBillingMail($permohonan, $createBilling));
                } catch (\Throwable $mailError) {
                    Log::warning('Gagal mengirim email tagihan ke pemohon: '.$mailError->getMessage(), [
                        'permohonan_id' => $id,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil divalidasi',
                'data' => $createBilling,
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function uploadBuktiPembayaran(Request $request)
    {
        try {
            $id = $request->permohonan_transaction_id;

            $validate = Validator::make($request->all(), [
                'bukti_pembayaran' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'validation_error',
                    'field' => 'bukti_pembayaran',
                    'message' => $validate->errors()->first(),
                    'all_errors' => $validate->errors(),
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
                'data' => $updateBilling,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
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
                'valid_at' => now(),
                'valid_by' => auth()->user()->id,
                'updated_at' => now(),
            ]);

            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'status' => 'MENUNGGU JADWAL PEMASANGAN',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil diverifikasi',
                'data' => $updateStatus,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function setPetugasPemasangan(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);

            $validate = Validator::make($request->all(), [
                'petugas_id' => 'required|exists:users,id',
                'ms_meteran_id' => 'required|exists:ms_meterans,id',
                'tgl_pasang' => 'required|date',
                'nomor_seri' => 'required|string|max:100',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'validation_error',
                    'field' => $validate->errors()->keys()[0] ?? null,
                    'message' => $validate->errors()->first(),
                    'all_errors' => $validate->errors(),
                ], 422);
            }

            $checkPermohonan = PermohonanTransaction::where('id', $id)->first();
            if (! $checkPermohonan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permohonan tidak ditemukan',
                ], 404);
            }

            if (PermohonanOfficer::where('id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Petugas pemasangan sudah ditetapkan',
                ], 422);
            }

            DB::beginTransaction();

            $permohonanOfficer = PermohonanOfficer::create([
                'id' => $id,
                'petugas_id' => $request->petugas_id,
                'tgl_pasang' => $request->tgl_pasang,
                'ms_meteran_id' => $request->ms_meteran_id,
                'nomor_seri' => $request->nomor_seri,
                'is_done' => false,
                'created_by' => auth()->id(),
            ]);

            PermohonanTransaction::where('id', $id)->update([
                'status' => 'TERJADWAL PEMASANGAN',
            ]);

            DB::commit();

            $checkPermohonan->refresh();
            $permohonanOfficer->load(['petugas', 'msMeteran']);

            $no_wa = $checkPermohonan->telepon;
            // Hilangkan whitespace dan strip
            $no_wa = preg_replace('/[\s\-]/', '', $no_wa);
            if (substr($no_wa, 0, 2) !== '62') {
                // Jika angka pertama 0 ubah ke 62, jika tidak tambahkan 62 di depan
                if (substr($no_wa, 0, 1) === '0') {
                    $no_wa = '62' . substr($no_wa, 1);
                } else {
                    $no_wa = '62' . $no_wa;
                }
            }

           $message = "Halo Saudara/i $checkPermohonan->nama,\n\n"
            . "Permohonan Anda telah dijadwalkan untuk pemasangan sambungan baru meter air.\n"
            . "Silakan menunggu petugas teknis datang untuk melakukan pemasangan.\n\n"
            . "Berikut ringkasan informasinya:\n\n"
            . "Nomor Registrasi: ".$checkPermohonan->no_register."\n"
            . "Nomor Pelanggan: ".$checkPermohonan->no_pelanggan."\n"
            . "Tanggal pemasangan: ".$permohonanOfficer->tgl_pasang."\n"
            . "Petugas Teknis: ".$permohonanOfficer->petugas->name."\n"
            . "Jenis meteran: ".$permohonanOfficer->msMeteran->nama."\n"
            . "Nomor meteran: ".$permohonanOfficer->nomor_seri."\n";
            
            $kirimWa = $this->kirimWa($no_wa, $message);            

            $pelanggan = User::find($id);
            if ($pelanggan && filter_var($pelanggan->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($pelanggan->email)->send(new PemohonanPemasanganMail($checkPermohonan, $permohonanOfficer));
                } catch (\Throwable $mailError) {
                    Log::warning('Gagal mengirim email jadwal pemasangan ke pelanggan: '.$mailError->getMessage(), [
                        'permohonan_id' => $id,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Petugas pemasangan berhasil ditetapkan',
                'data' => $permohonanOfficer,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function uploadDokumenTeknisi(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $permohonan = PermohonanTransaction::where('id', $id)->first();
            if (! $permohonan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permohonan tidak ditemukan',
                ], 404);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $file = $image->store('uploads/dokumen_teknisi', 'public');

                    $officerDocument = OfficerDocument::create([
                        'permohonan_transaction_id' => $permohonan->id,
                        'petugas_id' => auth()->user()->id,
                        'path' => $file,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah',
                'data' => $officerDocument,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function deleteDokumenTeknisi(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $officerDocument = OfficerDocument::where('id', $id)->first();
            if (! $officerDocument) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dokumen tidak ditemukan',
                ], 404);
            }
            $officerDocument->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function laporkanHasilPemasangan(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $permohonan = PermohonanTransaction::where('id', $id)->first();
            if (! $permohonan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permohonan tidak ditemukan',
                ], 404);
            }

            DB::beginTransaction();
            $updateStatus = PermohonanTransaction::where('id', $id)->update([
                'status' => 'PEMASANGAN SELESAI',
            ]);

            $updateOfficer = PermohonanOfficer::where('id', $id)->update([
                'is_done' => true,
                'done_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hasil pemasangan berhasil dilaporkan',
                'data' => $updateStatus,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function konfirmasiHasilPemasangan(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->permohonan_transaction_id);
            $permohonan = PermohonanTransaction::where('id', $id)->first();
            if (! $permohonan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permohonan tidak ditemukan',
                ], 404);
            }

            $update = PermohonanTransaction::where('id', $id)->update([
                'status' => 'SELESAI',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hasil pemasangan berhasil dikonfirmasi',
                'data' => $update,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function kirimWa($no_hp, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.api_key'),
        ])->post('https://api.fonnte.com/send', [
                    'target' => $no_hp,
                    'message' => $message,
                ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'target' => $no_hp,
                'target_message' => $message,
            ];
        }

        return 'Gagal kirim pesan';
    }
}
