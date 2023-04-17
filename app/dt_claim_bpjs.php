<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_claim_bpjs extends Model
{
    protected $table = 'dt_claim_bpjs';
    protected $primaryKey = 'id';

    protected $fillable = [
		  'waktu',
  		'dari',
  		'sampai',
  		'id_dpjp',
  		'nominal_inap',
  		'sisa_sebelum_inap',
  		'jumlah_inap',
  		'claim_inap',
  		'sisa_inap',
  		'medis_inap',
  		'nominal_jalan',
  		'sisa_sebelum_jalan',
  		'jumlah_jalan',
  		'claim_jalan',
  		'sisa_jalan',
  		'medis_jalan',
  		'stat',
  		'id_stat',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = []; 
}
