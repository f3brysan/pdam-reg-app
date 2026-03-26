<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsJenisDokumen extends Model
{
    protected $table = 'ms_jenis_dokumens';
    protected $fillable = ['id', 'nama', 'slug'];
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function permohonanDokumenTransactions()
    {
        return $this->hasMany(PermohonanDokumenTransaction::class, 'ms_jenis_dokumen_id', 'id');
    }
}
