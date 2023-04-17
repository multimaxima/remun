<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class PasienPerDPJP implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $awal, $akhir;

    function __construct($awal, $akhir) {
        $this->awal = $awal;
        $this->akhir = $akhir;
    }

    public function view(): View {
        $pasien     = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->selectRaw('CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,

                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,

                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,

                                     SUM(dt_pasien_layanan.tarif) as tarif,

                                     (SELECT 
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dokter,

                                     SUM(IF(dt_pasien_layanan.id_ruang = 11 OR dt_pasien_layanan.id_ruang_sub = 11,dt_pasien_layanan.js,0)) as ugd_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang = 11 OR dt_pasien_layanan.id_ruang_sub = 11,dt_pasien_layanan.jp,0)) as ugd_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang <> 11 AND dt_pasien_layanan.id_ruang_sub <> 11 AND dt_pasien_layanan.id_ruang_sub <> 29 AND dt_pasien_layanan.id_ruang_sub <> 5 AND dt_pasien_layanan.id_ruang_sub <>46 AND dt_pasien_layanan.id_ruang_sub <> 47 AND dt_pasien_layanan.id_ruang_sub <> 62 AND dt_pasien_layanan.id_ruang_sub <> 31 AND dt_pasien_layanan.id_ruang_sub <> 30,dt_pasien_layanan.js,0)) as ruang_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang <> 11 AND dt_pasien_layanan.id_ruang_sub <> 11 AND dt_pasien_layanan.id_ruang_sub <> 29 AND dt_pasien_layanan.id_ruang_sub <> 5 AND dt_pasien_layanan.id_ruang_sub <>46 AND dt_pasien_layanan.id_ruang_sub <> 47 AND dt_pasien_layanan.id_ruang_sub <> 62 AND dt_pasien_layanan.id_ruang_sub <> 31 AND dt_pasien_layanan.id_ruang_sub <> 30,dt_pasien_layanan.jp,0)) as ruang_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 29 OR dt_pasien_layanan.id_ruang_sub = 62,dt_pasien_layanan.js,0)) as laborat_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 29 OR dt_pasien_layanan.id_ruang_sub = 62,dt_pasien_layanan.jp,0)) as laborat_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 5,dt_pasien_layanan.js,0)) as operasi_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 5,dt_pasien_layanan.jp,0)) as operasi_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 46,dt_pasien_layanan.js,0)) as radiologi_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 46,dt_pasien_layanan.jp,0)) as radiologi_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 47,dt_pasien_layanan.js,0)) as rr_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 47,dt_pasien_layanan.jp,0)) as rr_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 31,dt_pasien_layanan.js,0)) as gizi_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 31,dt_pasien_layanan.jp,0)) as gizi_jp,

                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 30,dt_pasien_layanan.js,0)) as apotik_js,
                                     SUM(IF(dt_pasien_layanan.id_ruang_sub = 30,dt_pasien_layanan.jp,0)) as apotik_jp,

                                     SUM(dt_pasien_layanan.jasa_dpjp) as jasa_dpjp,
                                     SUM(dt_pasien_layanan.jasa_pengganti) as jasa_pengganti,
                                     SUM(dt_pasien_layanan.jasa_operator) as jasa_operator,
                                     SUM(dt_pasien_layanan.jasa_anastesi) as jasa_anastesi,
                                     SUM(dt_pasien_layanan.jasa_pendamping) as jasa_pendamping,
                                     SUM(dt_pasien_layanan.jasa_konsul) as jasa_konsul,
                                     SUM(dt_pasien_layanan.jasa_laborat) as jasa_laborat,
                                     SUM(dt_pasien_layanan.jasa_tanggung) as jasa_tanggung,
                                     SUM(dt_pasien_layanan.jasa_radiologi) as jasa_radiologi,
                                     SUM(dt_pasien_layanan.jasa_rr) as jasa_rr,

                                     SUM(dt_pasien_layanan.jp_perawat) as jp_perawat,
                                     SUM(dt_pasien_layanan.pen_anastesi) as pen_anastesi,
                                     SUM(dt_pasien_layanan.per_asisten_1) as per_asisten_1,
                                     SUM(dt_pasien_layanan.per_asisten_2) as per_asisten_2,
                                     SUM(dt_pasien_layanan.instrumen) as instrumen,
                                     SUM(dt_pasien_layanan.sirkuler) as sirkuler,
                                     SUM(dt_pasien_layanan.per_pendamping_1) as per_pendamping_1,
                                     SUM(dt_pasien_layanan.per_pendamping_2) as per_pendamping_2,
                                     SUM(dt_pasien_layanan.apoteker) as apoteker,
                                     SUM(dt_pasien_layanan.ass_apoteker) as ass_apoteker,
                                     SUM(dt_pasien_layanan.admin_farmasi) as admin_farmasi')

                        ->whereNotNull('dt_pasien.keluar')
                        ->whereDate('dt_pasien.keluar','>=',$this->awal)
                        ->whereDate('dt_pasien.keluar','<=',$this->akhir)
                        ->whereNotNull('dt_pasien_ruang.id_dpjp')
                        ->groupby('dt_pasien_ruang.id_dpjp')
                        ->groupby('users.nama')
                        ->groupby('dt_pasien_layanan.id_ruang')
                        ->groupby('dt_pasien_layanan.id_ruang_sub')
                        ->groupby('dt_pasien_layanan.id_dpjp_real')
                        ->orderby('users.nama')
                        ->get();

        $awal   = Carbon::parse($this->awal)->format('d M Y');
        $akhir  = Carbon::parse($this->akhir)->format('d M Y');
        
        return view('export.pasien_keluar_dpjp_export', compact('pasien','awal','akhir'));
    }    
}
