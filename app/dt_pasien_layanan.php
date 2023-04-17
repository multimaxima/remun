<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_pasien_layanan extends Model
{
    protected $table = 'dt_pasien_layanan';
    protected $primaryKey = 'id';

    protected $fillable = [
  		'waktu',
  		'id_petugas',
  		'id_pasien_ruang',
  		'id_pasien_layanan',
  		'id_pasien',
  		'id_jenis',
  		'jkn',
  		'keluar',
  		'id_ruang',
  		'id_ruang_sub',
  		'id_dpjp',
  		'id_dpjp_real',
  		'jasa_dpjp',
  		'id_pengganti',
  		'jasa_pengganti',
  		'id_operator',
  		'jasa_operator',
  		'id_anastesi',
  		'jasa_anastesi',
  		'id_pendamping',
  		'jasa_pendamping',
  		'id_konsul',
  		'jasa_konsul',
  		'id_laborat',
  		'jasa_laborat',
  		'id_tanggung',
  		'jasa_tanggung',
  		'id_radiologi',
  		'jasa_radiologi',
  		'id_rr',
  		'jasa_rr',
  		'medis',
  		'id_perhitungan',
  		'id_jasa',
  		'tarif',
  		'keterangan',
  		'stat',
  		'jp_perawat',
  		'pen_anastesi',
  		'per_asisten_1',
  		'per_asisten_2',
  		'instrumen',
  		'sirkuler',
  		'per_pendamping_1',
  		'per_pendamping_2',
  		'apoteker',
  		'ass_apoteker',
  		'admin_farmasi',
  		'created_at',
  		'updated_at',
    ];

    protected $hidden = [];
}
