<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_ruang extends Model
{
    protected $table = 'dt_ruang';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'ruang',
  		'jalan',
  		'inap',
  		'terima_pasien',
  		'nonpasien',
      'medis_khusus',
  		'hapus',
  		'waktu_hapus',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];    

    public function pasien()
    {
      return $this->HasMany('App\dt_pasien','id_ruang','id');
    }

    public function ruang_jasa()
    {
      return $this->HasMany('App\dt_ruang_jasa','id_ruang','id');
    }

    public function ruang_jenis()
    {
      return $this->HasMany('App\Models\dt_ruang_jenis','id_ruang','id');
    }
}
