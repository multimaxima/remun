<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_ruang_jasa extends Model
{
    protected $table = 'dt_ruang_jasa';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'id_ruang',
  		'id_jasa',
  		'hapus',
  		'waktu_hapus',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];  
}
