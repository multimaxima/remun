<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class RemunKwitansi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id 		= $id;
    }

    public function view(): View {
        $master = DB::table('dt_remun')
                    ->where('id',$this->id)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.id_bpjs,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,
                                 dt_remun.r_penyesuaian_operator,
                                 dt_remun.r_penyesuaian_spesialis,
                                 dt_remun.r_penyesuaian_umum,
                                 dt_remun.r_penyesuaian_perawat,
                                 dt_remun.r_penyesuaian_administrasi,
                                 dt_remun.r_penyesuaian_staf,
                                 dt_remun.r_penyesuaian,
                                 dt_remun.x_tpp')
                    ->first();

        $param  = DB::table('parameter')->first();

        $remun  = DB::table('dt_remun_detil')
                    ->where('dt_remun_detil.id_remun',$master->id)
                    ->selectRaw('dt_remun_detil.id_remun,
                                (SELECT dt_remun.tanggal FROM dt_remun WHERE dt_remun.id = dt_remun_detil.id_remun) AS tanggal,
  
                                SUM(dt_remun_detil.tpp) as tpp,    

                                ROUND(SUM(dt_remun_detil.tpp * (dt_remun_detil.pajak) /100)) as tpp_pajak,
 
                                SUM(dt_remun_detil.r_medis + dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) as jp,

                                SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100)) as jp_pajak,

                                SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi) as nonpenghasil,

                                ROUND(SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi) * dt_remun_detil.pajak /100)) as nonpenghasil_pajak,

                                SUM(dt_remun_detil.r_medis + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) as penghasil,

                                ROUND(SUM((dt_remun_detil.r_medis + dt_remun_detil.r_administrasi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak /100)) as penghasil_pajak,
   
                                SUM(dt_remun_detil.r_direksi) AS direksi,  
                                ROUND(SUM(dt_remun_detil.r_direksi * dt_remun_detil.pajak / 100)) AS direksi_pajak,
  
                                SUM(dt_remun_detil.r_staf_direksi) AS staf_direksi,
                                ROUND(SUM(dt_remun_detil.r_staf_direksi * dt_remun_detil.pajak / 100)) AS staf_pajak,

                                SUM(dt_remun_detil.r_penyesuaian) AS penyesuaian,
                                ROUND(SUM(dt_remun_detil.r_penyesuaian * dt_remun_detil.pajak / 100)) AS penyesuaian_pajak,
  
                                SUM(dt_remun_detil.r_pos_remun) AS pos_remun,
                                ROUND(SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek) * dt_remun_detil.pajak / 100)) AS pos_remun_pajak,

                                SUM(dt_remun_detil.r_insentif_perawat) AS insentif_perawat,
                                ROUND(SUM(dt_remun_detil.r_insentif_perawat * dt_remun_detil.pajak / 100)) AS insentif_perawat_pajak,
  
                                SUM(dt_remun_detil.r_administrasi) AS administrasi,
                                ROUND(SUM(dt_remun_detil.r_administrasi * dt_remun_detil.pajak / 100)) AS administrasi_pajak,

                                SUM(dt_remun_detil.r_medis + dt_remun_detil.alokasi_apotik) AS medis,
                                ROUND(SUM((dt_remun_detil.r_medis + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100)) AS medis_pajak,

                                SUM(dt_remun_detil.r_penyesuaian) * '.$param->peny_operator.' / 100 as operator,
                                ROUND(SUM(dt_remun_detil.r_penyesuaian * dt_remun_detil.pajak / 100) * 
                                '.$param->peny_operator.' / 100) as operator_pajak')

                    ->groupby('dt_remun_detil.id_remun')
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

                                 parameter.direktur_plt,
                                 
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as direktur,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as nip_direktur')
                    ->first();

        return view('export.remunerasi_kwitansi',compact('remun','master','param'));
    }
}
