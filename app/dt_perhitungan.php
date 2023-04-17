<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_perhitungan extends Model
{
    protected $table = 'dt_perhitungan';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'id_jenis_pasien',
  		'id_kategori_jasa',
  		'id_jasa',
  		'hapus',
  		'tgl_hapus',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];    

    public function perhitungan_1()
    {
      return $this->HasMany('App\dt_perhitungan_1','id_perhitungan','id');
    }
}
