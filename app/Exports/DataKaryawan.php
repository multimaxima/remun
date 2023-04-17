<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class DataKaryawan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $karyawan    = DB::table('users')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                       ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                       ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
                       ->leftjoin('users_status','users.id_status','=','users_status.id')
                       ->selectRaw('users.id,
                                    users.foto,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.nip,
                                    users.username,
                                    users.gapok,
                                    users.golongan,
                                    users.npwp,
                                    users_status.status,
                                    
                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as indeks_kerja,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,

                                    users.pajak,
                                    users.tpp,

                                    DATE_FORMAT(users.mulai_kerja,"%d %M %Y") as mulai_kerja,
                                    
                                    IF(users.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW()), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,NOW()) % 12, " Bln."),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,users.keluar) % 12, " Bln.")) as masa_kerja,

                                    users_tenaga_bagian.bagian,
                                    users_tenaga.tenaga,
                                    users.rekening,
                                    dt_ruang.ruang,
                                    users_akses.akses')
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->get();

        return view('export.karyawan_export', compact('karyawan'));
    }    
}
