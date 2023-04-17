<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class RemunPembayaran implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id 		= $id;
    }

    public function view(): View {
    	$remun  = DB::table('dt_remun')
                    ->where('id',$this->id)
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir')
                    ->first();

        $detil  = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_status','users.id_status','=','users_status.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                    ->selectRaw('dt_remun_detil.id,
                                 dt_remun_detil.id_remun,
                                 users_tenaga_bagian.urut_bagian,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                 dt_remun_detil.score_real as score,
                                 users_status.status,
                                 UPPER(users.golongan) as golongan,
                                 dt_remun_detil.r_pos_remun, 
                                 dt_remun_detil.r_indek, 
                                 dt_remun_detil.r_penyesuaian, 
                                 dt_remun_detil.r_insentif_perawat, 
                                 dt_remun_detil.r_direksi, 
                                 dt_remun_detil.r_staf_direksi,
                                 dt_remun_detil.r_administrasi, 
                                 dt_remun_detil.alokasi_apotik + dt_remun_detil.r_medis as r_medis,
                                 dt_remun_detil.tpp, 

                                 (dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) AS jasa,

                                 dt_remun_detil.pajak,

                                 ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak) / 100) AS nom_pajak,

                                 (dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) - ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak) / 100) AS total,
                                 
                                 dt_ruang.ruang,
                                 users.npwp,
                                 users.rekening,
                                 users.bank')
                    ->where('dt_remun_detil.id_remun',$remun->id)
                    ->orderby('users_tenaga_bagian.urut_bagian')
                    ->orderby('dt_ruang.ruang')
                    ->orderby('users_status.status')
                    ->orderby('users.nama')
                    ->get();

        $total  = DB::table('dt_remun_detil')
                    ->selectRaw('SUM(dt_remun_detil.r_indek) as r_indek,
                                 SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian,
                                 SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat, 
                                 SUM(dt_remun_detil.r_direksi) as r_direksi, 
                                 SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                 SUM(dt_remun_detil.r_administrasi) as r_administrasi, 
                                 SUM(dt_remun_detil.r_medis + dt_remun_detil.alokasi_apotik) as r_medis,
                                 SUM(dt_remun_detil.tpp) as tpp, 
                                 
                                 SUM((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik)) AS jasa,
                                 
                                 SUM(ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak) / 100)) AS nom_pajak,

                                 SUM((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) - ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak) / 100)) AS total')
                    ->where('dt_remun_detil.id_remun',$remun->id)
                    ->first();

        $param  = DB::table('parameter')
                    ->selectRaw('(SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as ketua,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as nip_ketua,
                                 
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as direktur,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as nip_direktur,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_bendahara) as bendahara,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_bendahara) as nip_bendahara,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_pelaksana) as pelaksana,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_pelaksana) as nip_pelaksana')
                    ->first();

        return view('export.remunerasi_pembayaran',compact('remun','detil','total','param'));
    }
}
