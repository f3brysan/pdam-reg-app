<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsJenisTempatTinggal extends Model
{
    protected $table = 'ms_jenis_tempat_tinggals';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['id', 'nama'];

    public function permohonanTransactions()
    {
        return $this->hasMany(PermohonanTransaction::class, 'ms_jenis_tempat_tinggal_id', 'id');
    }
}
