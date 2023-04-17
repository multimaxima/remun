<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class IndeksKaryawan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $karyawan    = DB::table('users')  
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')      
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.pendidikan,

                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users.dasar_bobot,
                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot as skor_indek,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as masa_kerja,

                                    users.masa_kerja_bobot,

                                    ((IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)))) * users.masa_kerja_bobot) as indeks_masa_kerja,

                                    ((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 
                                    ((IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)))) * users.masa_kerja_bobot)) / 2 as skor_dasar,

                                    users.pend_nilai,
                                    users.pend_bobot,
                                    (users.pend_nilai * users.pend_bobot) AS skor_pend,
                                    users.diklat_nilai,
                                    users.diklat_bobot,
                                    (users.diklat_nilai * users.diklat_bobot) AS skor_diklat,
                                    (users.pend_nilai * users.pend_bobot) + (users.diklat_nilai * users.diklat_bobot) AS indeks_komp,
                                    users.temp_tugas,
                                    users.resiko_nilai,
                                    users.resiko_bobot,
                                    (users.resiko_nilai * users.resiko_bobot) AS indeks_resiko,
                                    users.gawat_nilai,
                                    users.gawat_bobot,
                                    (users.gawat_nilai * users.gawat_bobot) AS indeks_kegawat,
                                    users.jabatan,
                                    users.jab_nilai,
                                    users.jab_bobot,
                                    (users.jab_nilai * users.jab_bobot) AS skor_jab,
                                    users.panitia_nilai,
                                    users.panitia_bobot,
                                    (users.panitia_nilai * users.panitia_bobot) AS skor_pan,
                                    (users.jab_nilai * users.jab_bobot) + (users.panitia_nilai * users.panitia_bobot) AS indeks_jabatan,
                                    users.perform_nilai,
                                    users.perform_bobot,
                                    (users.perform_nilai * users.perform_bobot) AS indeks_perform,

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
                                    (users.perform_nilai * users.perform_bobot)) as total_indeks') 
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->get();

        return view('export.karyawan_indeks_export', compact('karyawan'));
    }    
}
