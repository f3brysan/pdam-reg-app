<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPekerjaan extends Model
{
    protected $table = 'ms_pekerjaans';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['id', 'nama'];

    public function permohonanTransactions()
    {
        return $this->hasMany(PermohonanTransaction::class, 'ms_pekerjaan_id', 'id');
    }
}
