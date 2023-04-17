<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class RemunBackupExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id     = $id;
    }

    public function view(): View {
        $remun  = DB::table('dt_remun_back')
                    ->where('dt_remun_back.id',$this->id)
                    ->selectRaw('dt_remun_back.id,
                                 dt_remun_back.tanggal,
                                 DATE_FORMAT(dt_remun_back.awal,"%d %M %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun_back.akhir,"%d %M %Y") as tgl_akhir,
                                 dt_remun_back.id_bpjs,
                                 dt_remun_back.awal,
                                 dt_remun_back.akhir,
                                 dt_remun_back.tpp,
                                 dt_remun_back.jp,
                                 dt_remun_back.penghasil,
                                 dt_remun_back.nonpenghasil,
                                 dt_remun_back.medis_perawat,
                                 dt_remun_back.admin,
                                 dt_remun_back.pos_remun,
                                 dt_remun_back.indek,
                                 dt_remun_back.direksi,
                                 dt_remun_back.staf,
                                 dt_remun_back.kel_perawat,
                                 
                                 (dt_remun_back.admin +
                                  dt_remun_back.indek +
                                  dt_remun_back.tpp + 
                                  dt_remun_back.direksi +
                                  dt_remun_back.staf +
                                  IFNULL(dt_remun_back.kel_perawat,0)) as indeks,  

                                 dt_remun_back.a_jp,
                                 dt_remun_back.r_jp,
                                 dt_remun_back.r_penghasil,
                                 dt_remun_back.r_nonpenghasil,
                                 dt_remun_back.r_medis_perawat,
                                 dt_remun_back.r_admin,
                                 dt_remun_back.r_pos_remun,
                                 dt_remun_back.r_indek,
                                 dt_remun_back.r_direksi,
                                 dt_remun_back.r_staf,
                                 dt_remun_back.r_kel_perawat,

                                 (dt_remun_back.r_admin +
                                  dt_remun_back.r_indek +
                                  dt_remun_back.tpp +
                                  dt_remun_back.r_direksi +
                                  dt_remun_back.r_staf +
                                  IFNULL(dt_remun_back.r_kel_perawat,0)) as r_indeks,
                                 dt_remun_back.stat')
                    ->first();                    

        $rincian    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil_back.id,                                    
                                    dt_remun_detil_back.score,
                                    dt_remun_detil_back.tpp,
                                    dt_remun_detil_back.pajak,
                                    dt_remun_detil_back.pos_remun,
                                    dt_remun_detil_back.insentif_perawat,
                                    dt_remun_detil_back.direksi,
                                    dt_remun_detil_back.staf_direksi,
                                    dt_remun_detil_back.administrasi,
                                    dt_remun_detil_back.pos_remun + dt_remun_detil_back.insentif_perawat + dt_remun_detil_back.staf_direksi + dt_remun_detil_back.administrasi + dt_remun_detil_back.direksi AS total_indek,

                                    dt_remun_detil_back.medis as medis,
                                    dt_remun_detil_back.medis + dt_remun_detil_back.titipan as jumlah,

                                    dt_remun_detil_back.pos_remun + dt_remun_detil_back.insentif_perawat + dt_remun_detil_back.staf_direksi + dt_remun_detil_back.administrasi + dt_remun_detil_back.medis + dt_remun_detil_back.titipan + dt_remun_detil_back.direksi AS jasa_pelayanan,
                                    dt_remun_detil_back.r_pos_remun,
                                    dt_remun_detil_back.r_indek,
                                    dt_remun_detil_back.r_insentif_perawat,
                                    dt_remun_detil_back.r_direksi,
                                    dt_remun_detil_back.r_staf_direksi,
                                    dt_remun_detil_back.r_administrasi,
                                    dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_direksi AS r_total_indek,
                                    
                                    dt_remun_detil_back.titipan + dt_remun_detil_back.r_medis as r_medis,

                                    dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan AS r_jasa_pelayanan,

                                    ROUND((dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) * dt_remun_detil_back.pajak / 100) as nominal_pajak,

                                    (dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) - 
                                    ROUND(((dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) * dt_remun_detil_back.pajak / 100)) as sisa,

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
                        ->where('dt_remun_detil_back.id_remun',$this->id)
                        ->where('dt_remun_detil_back.ke',2)
                        ->orderby('users_tenaga_bagian.urut')
                        ->orderby('dt_ruang.ruang')
                        ->orderby('users_tenaga_bagian.bagian')
                        ->orderby('users.nama')
                        ->get();

        $jumlah    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil_back.tpp) as tpp,
                                     SUM(dt_remun_detil_back.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil_back.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil_back.direksi) as direksi,
                                     SUM(dt_remun_detil_back.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil_back.administrasi) as administrasi,
                                     SUM(dt_remun_detil_back.pos_remun + dt_remun_detil_back.insentif_perawat + dt_remun_detil_back.staf_direksi + dt_remun_detil_back.administrasi + dt_remun_detil_back.direksi) AS total_indek,

                                     SUM(dt_remun_detil_back.medis) as medis,
                                     SUM(dt_remun_detil_back.medis + dt_remun_detil_back.titipan) as jumlah,

                                     SUM(dt_remun_detil_back.pos_remun + dt_remun_detil_back.insentif_perawat + dt_remun_detil_back.staf_direksi + dt_remun_detil_back.administrasi + dt_remun_detil_back.medis + dt_remun_detil_back.titipan + dt_remun_detil_back.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil_back.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil_back.r_indek) as r_indek,
                                     SUM(dt_remun_detil_back.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil_back.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil_back.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil_back.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_direksi) AS r_total_indek,

                                     SUM(dt_remun_detil_back.r_medis) as r_medis,
                                     SUM(dt_remun_detil_back.titipan) as titipan,
                                     SUM(dt_remun_detil_back.titipan + dt_remun_detil_back.r_medis) as r_jumlah,

                                     SUM(dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) AS r_jasa_pelayanan,

                                     SUM(ROUND((dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) * dt_remun_detil_back.pajak / 100)) as nominal_pajak,

                                    SUM((dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) - 
                                    ROUND(((dt_remun_detil_back.tpp + dt_remun_detil_back.r_indek + dt_remun_detil_back.r_insentif_perawat + dt_remun_detil_back.r_staf_direksi + dt_remun_detil_back.r_administrasi + dt_remun_detil_back.r_medis + dt_remun_detil_back.r_direksi + dt_remun_detil_back.titipan) * dt_remun_detil_back.pajak / 100))) as sisa')
                        ->where('dt_remun_detil_back.id_remun',$this->id)
                        ->where('dt_remun_detil_back.ke',2)
                        ->first();

        return view('export.remunerasi', compact('remun','rincian','jumlah'));
    }
}
