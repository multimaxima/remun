<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_ruang_jenis extends Model
{
    use HasFactory;

    protected $table = 'dt_ruang_jenis';
    protected $primaryKey = 'id';

    protected $fillable = [
      'id_ruang',
      'id_pasien_jenis',
      'jenis',
      'created_at',
      'updated_at',
      'petugas_create',
      'petugas_update',
    ];

    protected $hidden = [];  
}
