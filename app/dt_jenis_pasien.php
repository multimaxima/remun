<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_jenis_pasien extends Model
{
    protected $table = 'dt_jenis_pasien';
    protected $primaryKey = 'id';

    protected $fillable = [
		  'jenis_pasien',
  		'kode',
  		'inap',
  		'jkn',
  		'hapus',
  		'waktu_hapus',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];  

    public function pasien()
    {
      return $this->HasMany('App\dt_pasien','id_jenis','id');
    }  

    public function pasien_layanan()
    {
      return $this->HasMany('App\dt_pasien_layanan','id_jenis','id');
    }  

    public function pasien_ruang()
    {
      return $this->HasMany('App\dt_pasien_ruang','id_jenis','id');
    }  
}
