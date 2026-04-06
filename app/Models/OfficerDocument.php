<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerDocument extends Model
{
    protected $table = 'officer_documents';
    protected $fillable = ['id', 'permohonan_transaction_id', 'petugas_id', 'path', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function permohonanTransaction()
    {
        return $this->belongsTo(PermohonanTransaction::class, 'permohonan_transaction_id', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }
}
