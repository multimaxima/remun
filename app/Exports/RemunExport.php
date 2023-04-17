<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class RemunExport implements FromView
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
                    ->where('dt_remun.id',$this->id)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                 dt_remun.id_bpjs,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 dt_remun.tpp,
                                 dt_remun.jp,
                                 dt_remun.penghasil,
                                 dt_remun.nonpenghasil,
                                 dt_remun.medis_perawat,
                                 dt_remun.admin,
                                 dt_remun.pos_remun,
                                 dt_remun.indek,
                                 dt_remun.direksi,
                                 dt_remun.staf,
                                 dt_remun.kel_perawat,
                                 
                                 (dt_remun.admin +
                                  dt_remun.indek +
                                  dt_remun.tpp + 
                                  dt_remun.direksi +
                                  dt_remun.staf +
                                  IFNULL(dt_remun.kel_perawat,0)) as indeks,  

                                 dt_remun.a_jp,
                                 dt_remun.r_jp,
                                 dt_remun.r_penghasil,
                                 dt_remun.r_nonpenghasil,
                                 dt_remun.r_medis_perawat,
                                 dt_remun.r_admin,
                                 dt_remun.r_pos_remun,
                                 dt_remun.r_indek,
                                 dt_remun.r_direksi,
                                 dt_remun.r_staf,
                                 dt_remun.r_kel_perawat,

                                 (dt_remun.r_admin +
                                  dt_remun.r_indek +
                                  dt_remun.tpp +
                                  dt_remun.r_direksi +
                                  dt_remun.r_staf +
                                  IFNULL(dt_remun.r_kel_perawat,0)) as r_indeks,
                                 dt_remun.stat')
                    ->first();                    

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil.id,                                    
                                    dt_remun_detil.score_real as score,
                                    dt_remun_detil.tpp,
                                    dt_remun_detil.pajak,
                                    dt_remun_detil.pos_remun,
                                    dt_remun_detil.insentif_perawat,
                                    dt_remun_detil.direksi,
                                    dt_remun_detil.staf_direksi,
                                    dt_remun_detil.administrasi,
                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi AS total_indek,
                                    
                                    dt_remun_detil.medis as medis,
                                    dt_remun_detil.medis + dt_remun_detil.titipan as jumlah,

                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.titipan + dt_remun_detil.direksi AS jasa_pelayanan,
                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,
                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,
                                    
                                    dt_remun_detil.r_medis,
                                    dt_remun_detil.titipan,
                                    dt_remun_detil.r_medis + dt_remun_detil.titipan as r_jumlah,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan AS r_jasa_pelayanan,

                                    ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100) as nominal_pajak,

                                    (dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100)) as sisa,

                                    users.jabatan,
                                    users.id_ruang,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$this->id)
                        ->orderby('users_tenaga_bagian.urut')
                        ->orderby('dt_ruang.ruang')
                        ->orderby('users_tenaga_bagian.bagian')
                        ->orderby('users.nama')
                        ->get();

        $jumlah    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil.tpp) as tpp,
                                     SUM(dt_remun_detil.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil.direksi) as direksi,
                                     SUM(dt_remun_detil.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil.administrasi) as administrasi,
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi) AS total_indek,

                                     SUM(dt_remun_detil.medis) as medis,
                                     SUM(dt_remun_detil.medis + dt_remun_detil.titipan) as jumlah,

                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.titipan + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.titipan) as titipan,
                                     SUM(dt_remun_detil.r_medis + dt_remun_detil.titipan) as r_jumlah,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) AS r_jasa_pelayanan,

                                     SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100)) as nominal_pajak,

                                    SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100))) as sisa')
                        ->where('dt_remun_detil.id_remun',$this->id)
                        ->first();

        return view('export.remunerasi', compact('remun','rincian','jumlah'));
    }
}
