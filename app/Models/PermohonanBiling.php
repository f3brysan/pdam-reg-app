<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanBiling extends Model
{
    protected $table = 'permohonan_billings';
    protected $fillable = ['id', 'no_va', 'path', 'is_valid', 'price', 'created_at', 'updated_at'];

    public function permohonan()
    {
        return $this->hasOne(PermohonanTransaction::class, 'id', 'id');
    }
}
