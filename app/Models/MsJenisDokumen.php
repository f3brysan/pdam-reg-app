<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsJenisDokumen extends Model
{
    protected $table = 'ms_jenis_dokumens';
    protected $fillable = ['id', 'nama', 'slug'];
    protected $primaryKey = 'id';
    public $timestamps = true;
}
