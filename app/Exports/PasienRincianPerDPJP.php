<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class PasienRincianPerDPJP implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $awal, $akhir, $id_ruang, $id_dpjp;

    function __construct($awal, $akhir, $id_ruang, $id_dpjp) {
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->id_dpjp = $id_dpjp;
        $this->id_ruang = $id_ruang;
    }

    public function view(): View
    {
      $id_ruang   = $this->id_ruang;
      $id_dpjp    = $this->id_dpjp;

    	$pasien     = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     dt_pasien_layanan.id_ruang,
                                     dt_pasien_layanan.id_ruang_sub,
                                     dt_pasien_layanan.id_dpjp,

                                     (SELECT 
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                     
                                     (SELECT 
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,

                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i:%s") as waktu,
                                     dt_pasien.nama as nama_pasien,
                                     dt_pasien.no_mr,
                                     DATE_FORMAT(dt_pasien.masuk,"%d/%m/%Y - %H:%i:%s") as masuk,
                                     DATE_FORMAT(dt_pasien.keluar,"%d/%m/%Y - %H:%i:%s") as keluar,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,
                                     dt_jasa.jasa,
                                     dt_pasien_layanan.tarif,
                                     dt_pasien_layanan.n_js,
                                     dt_pasien_layanan.js,
                                     dt_pasien_layanan.n_jp,
                                     dt_pasien_layanan.jp,
                                     dt_pasien_layanan.n_profit,
                                     dt_pasien_layanan.profit,
                                     dt_pasien_layanan.n_penghasil,
                                     dt_pasien_layanan.penghasil,
                                     dt_pasien_layanan.n_non_penghasil,
                                     dt_pasien_layanan.non_penghasil,                                     

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,
                                     dt_pasien_layanan.jasa_dpjp,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,
                                     dt_pasien_layanan.jasa_pengganti,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                     dt_pasien_layanan.jasa_operator,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,
                                     dt_pasien_layanan.jasa_anastesi,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,
                                     dt_pasien_layanan.jasa_pendamping,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                     dt_pasien_layanan.jasa_konsul,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_laborat) as laborat,
                                     dt_pasien_layanan.jasa_laborat,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung,
                                     dt_pasien_layanan.jasa_tanggung,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_radiologi) as radiologi,
                                     dt_pasien_layanan.jasa_radiologi,

                                     (SELECT
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_layanan.id_rr) as rr,

                                     dt_pasien_layanan.jasa_rr,                                     
                                     dt_pasien_layanan.jp_perawat,
                                     dt_pasien_layanan.pen_anastesi,
                                     dt_pasien_layanan.per_asisten_1,
                                     dt_pasien_layanan.per_asisten_2,
                                     dt_pasien_layanan.instrumen,
                                     dt_pasien_layanan.sirkuler,
                                     dt_pasien_layanan.per_pendamping_1,
                                     dt_pasien_layanan.per_pendamping_2,
                                     dt_pasien_layanan.apoteker,
                                     dt_pasien_layanan.ass_apoteker,
                                     dt_pasien_layanan.admin_farmasi')
                        ->whereNotNull('dt_pasien.keluar')
                        ->whereDate('dt_pasien.keluar','>=',$this->awal)
                        ->whereDate('dt_pasien.keluar','<=',$this->akhir)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                        ->orderby('dt_pasien.nama')
                        ->orderby('dt_pasien_jenis.jenis')
                        ->orderby('dt_pasien_layanan.id_ruang')
                        ->get();

        $awal   = Carbon::parse($this->awal)->format('d M Y');
        $akhir  = Carbon::parse($this->akhir)->format('d M Y');

        return view('export.pasien_keluar_rincian_dpjp_export', compact('pasien','awal','akhir'));
    }
}
