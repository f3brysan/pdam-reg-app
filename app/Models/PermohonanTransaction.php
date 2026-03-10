<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanTransaction extends Model
{
    protected $table = 'permohonan_transactions';
    protected $fillable = ['id', 'no_register', 'nik', 'nama', 'alamat', 'ms_pekerjaan_id', 'jumlah_keluarga', 'ms_jenis_tempat_tinggal_id', 'jumlah_kran', 'sedia_bayar', 'no_pelanggan', 'petugas_id', 'tgl_register', 'tgl_pasang', 'ms_meteran_id', 'no_seri_meteran'];
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

    public function msMeteran()
    {
        return $this->belongsTo(MsMeteran::class, 'ms_meteran_id', 'id');
    }
}
