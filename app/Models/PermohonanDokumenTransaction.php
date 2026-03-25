<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanDokumenTransaction extends Model
{
    protected $table = 'permohonan_dokumen_transactions';
    protected $fillable = ['permohonan_transaction_id', 'ms_jenis_dokumen_id', 'path', 'size'];
    protected $primaryKey = ['permohonan_transaction_id', 'ms_jenis_dokumen_id'];
    public $incrementing = false;
    public $timestamps = true;

    public function permohonanTransaction()
    {
        return $this->belongsTo(PermohonanTransaction::class, 'permohonan_transaction_id', 'id');
    }

    public function msJenisDokumen()
    {
        return $this->belongsTo(MsJenisDokumen::class, 'ms_jenis_dokumen_id', 'id');
    }
}
