<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_perhitungan_1 extends Model
{
    protected $table = 'dt_perhitungan_1';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'id_perhitungan',
  		'id_rekening',
  		'nilai',
  		'hapus',
  		'tgl_hapus',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];  

    public function perhitungan_2()
    {
      return $this->HasMany('App\dt_perhitungan_2','id_perhitungan_1','id');
    }
}
