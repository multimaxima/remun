<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_pasien_ruang extends Model
{
    protected $table = 'dt_pasien_ruang';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'id_pasien',
  		'id_ruang',
  		'id_ruang_sub',
  		'id_jenis',
  		'masuk',
  		'keluar',
  		'id_petugas',
  		'id_dpjp',
  		'id_dpjp_real',
  		'stat',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];    

    public function pasien_layanan()
    {
      return $this->HasMany('App\dt_pasien_layanan','id_pasien_ruang','id');
    }
}
