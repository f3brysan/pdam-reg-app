<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanOfficer extends Model
{
    protected $table = 'permohonan_officers';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'petugas_id', 'tgl_pasang', 'ms_meteran_id', 'nomor_seri', 'is_done', 'done_at', 'created_at', 'updated_at', 'created_by'];
    
    public function permohonanTransaction()
    {
        return $this->belongsTo(PermohonanTransaction::class, 'id', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }

    public function msMeteran()
    {
        return $this->belongsTo(MsMeteran::class, 'ms_meteran_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
