<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_remun extends Model
{
    protected $table = 'dt_remun';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'tanggal',
  		'awal',
  		'akhir',
  		'tpp',
  		'jp',
  		'penghasil',
  		'nonpenghasil',
  		'medis_perawat',
  		'admin',
  		'pos_remun',
  		'direksi',
  		'staf',
  		'kel_perawat',
  		'a_jp',
  		'r_jp',
  		'r_penghasil',
  		'r_nonpenghasil',
  		'r_medis_perawat',
  		'r_admin',
  		'r_pos_remun',
  		'r_direksi',
  		'r_staf',
  		'r_kel_perawat',
  		'score_total',
  		'score_pos_remun',
  		'score_perawat',
  		'score_direksi',
  		'score_admin',
  		'score_staf',
  		'score_medis',
  		'score_per_pend_1',
  		'score_per_pend_2',
  		'h_medis_perawat',
  		'id_bpjs',
  		'stat',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];    

    public function detil()
    {
      return $this->HasMany('App\dt_remun_detil','id_remun','id');
    }
}
