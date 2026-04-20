<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanTransaction extends Model
{
    protected $table = 'permohonan_transactions';
    protected $fillable = ['id', 'no_register', 'tgl_daftar', 'nik', 'nama', 'telepon', 'alamat', 'ms_pekerjaan_id', 'jumlah_keluarga', 'ms_jenis_tempat_tinggal_id', 'kecamatan', 'kelurahan', 'longitude', 'latitude', 'jumlah_kran', 'sedia_bayar', 'no_pelanggan', 'status'];
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function msPekerjaan()
    {
        return $this->belongsTo(MsPekerjaan::class, 'ms_pekerjaan_id', 'id');
    }

    public function msJenisTempatTinggal()
    {
        return $this->belongsTo(MsJenisTempatTinggal::class, 'ms_jenis_tempat_tinggal_id', 'id');
    }

    public function permohonanDokumenTransactions()
    {
        return $this->hasMany(PermohonanDokumenTransaction::class, 'permohonan_transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function permohonanBiling()
    {
        return $this->hasOne(PermohonanBiling::class, 'id', 'id');
    }

    public function permohonanOfficer()
    {
        return $this->hasOne(PermohonanOfficer::class, 'id', 'id');
    }
}
