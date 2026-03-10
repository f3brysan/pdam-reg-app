<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsMeteran extends Model
{
    protected $table = 'ms_meterans';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['id', 'nama'];

    public function permohonanTransactions()
    {
        return $this->hasMany(PermohonanTransaction::class, 'ms_meteran_id', 'id');
    }
}
