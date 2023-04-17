<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class JasaKaryawan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $karyawan   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                   users.pos_remun,
                                   users.direksi,
                                   users.staf,
                                   users.jp_admin,
                                   users.jp_perawat,
                                   users.insentif_perawat,
                                   users.apoteker,
                                   users.ass_apoteker,
                                   users.admin_farmasi,
                                   users.pen_anastesi,
                                   users.per_asisten_1,
                                   users.per_asisten_2,
                                   users.instrumen,
                                   users.sirkuler,
                                   users.per_pendamping_1,
                                   users.per_pendamping_2')
                      ->where('users.hapus',0)
                      ->where('users.id','>',1)
                      ->orderby('users_tenaga_bagian.urut')
                      ->orderby('users.nama')
                      ->get();

        return view('export.karyawan_jasa_export', compact('karyawan'));
    }    
}
