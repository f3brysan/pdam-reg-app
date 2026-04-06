<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanBiling extends Model
{
    protected $table = 'permohonan_billings';
    protected $fillable = ['id', 'no_va', 'price', 'path', 'is_valid', 'valid_at', 'valid_by', 'created_at', 'updated_at'];

    public function permohonan()
    {
        return $this->hasOne(PermohonanTransaction::class, 'id', 'id');
    }

    public function validBy()
    {
        return $this->belongsTo(User::class, 'valid_by', 'id');
    }
}
