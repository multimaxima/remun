<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dt_remun_detil extends Model
{
    protected $table = 'dt_remun_detil';
    protected $primaryKey = 'id';

    protected $fillable = [
		'id_remun',
		'id_karyawan',
		'tpp',
		'pajak',
		'score',
		'pos_remun',
		'r_pos_remun',
		'insentif_perawat',
		'r_insentif_perawat',
		'direksi',
		'r_direksi',
		'staf_direksi',
		'r_staf_direksi',
		'administrasi',
		'r_administrasi',
		'h_medis',
		'medis',
		'r_medis',
		'medis_dokter',
		'medis_perawat',
		'medis_apoteker',
		'medis_ass_apoteker',
		'admin_farmasi',
		'medis_pen_anastesi',
		'medis_per_asisten_1',
		'medis_per_asisten_2',
		'medis_instrumen',
		'medis_sirkuler',
		'medis_per_pend_1',
		'medis_per_pend_2',
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
		'adm_farmasi',
		'id_ruang',
		'id_ruang_1',
		'created_at',
		'updated_at',
    ];

    protected $hidden = [];   
}
