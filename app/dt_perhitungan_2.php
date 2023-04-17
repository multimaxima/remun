<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_perhitungan_2 extends Model
{
    protected $table = 'dt_perhitungan_2';
    protected $primaryKey = 'id';

    protected $fillable = [
		'id_perhitungan',
		'id_perhitungan_1',
		'id_rekening',
		'nilai',
		'hapus',
		'tgl_hapus',
		'created_at',
		'updated_at',
    ];

    protected $hidden = [];  

    public function perhitungan_3()
    {
      return $this->HasMany('App\dt_perhitungan_3','id_perhitungan_2','id');
    }
}
