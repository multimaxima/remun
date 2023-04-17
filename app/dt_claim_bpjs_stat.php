<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_claim_bpjs_stat extends Model
{
    protected $table = 'dt_claim_bpjs_stat';
    protected $primaryKey = 'id';

    protected $fillable = [
		  'awal',
  		'akhir',
  		'stat',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = []; 

    public function detil()
    {
      return $this->HasMany('App\dt_claim_bpjs','id_stat','id');
    }
}
