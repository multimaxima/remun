<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_pasien extends Model
{
    protected $table = 'dt_pasien';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'register',
  		'tgl_data',
  		'nama',
  		'alamat',
  		'temp_lahir',
  		'tgl_lahir',
  		'id_kelamin',
  		'no_mr',
  		'id_jenis',
  		'id_petugas',
  		'id_ruang',
  		'id_ruang_asal',
  		'masuk',
  		'keluar',
  		'keterangan',
  		'upp',
  		'waktu_upp',
  		'id_petugas_upp',
  		'non_pasien',
  		'stat',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];    

    public function pasien_ruang()
    {
      return $this->HasMany('App\dt_pasien_ruang','id_pasien','id');
    }

    public function pasien_layanan()
    {
      return $this->HasMany('App\dt_pasien_layanan','id_pasien','id');
    }

    public function jenis_pasien()
    {
      return $this->belongsTo('App\dt_jenis_pasien','id_jenis','id');
    }
}
