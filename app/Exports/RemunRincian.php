<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use App\dt_remun_detil;
use App\dt_pasien_layanan;
use DB;

class RemunRincian implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id 		= $id;
    }

    public function view(): View {
        $detil  = DB::table('dt_remun_detil')
                    ->leftjoin('dt_remun','dt_remun_detil.id_remun','=','dt_remun.id')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                    ->selectRaw('dt_remun_detil.id, 
                                 dt_remun.id_bpjs,
                                 dt_remun_detil.id_remun,
                                 dt_remun_detil.id_karyawan,
                                 dt_remun_detil.score_real as skore,
                                 dt_remun_detil.tpp,
                                 dt_remun_detil.r_indek,
                                 dt_remun_detil.r_insentif_perawat,
                                 dt_remun_detil.r_staf_direksi,
                                 dt_remun_detil.r_administrasi,
                                 dt_remun_detil.r_direksi,
                                 dt_remun_detil.alokasi_apotik,

                                 DATE_FORMAT(users.mulai_kerja,"%d %M %Y") as mulai_kerja,

                                 IF(users.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW()), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,NOW()) % 12, " Bln."),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,users.keluar) % 12, " Bln.")) as masa_kerja,

                                 dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,

                                 dt_remun_detil.r_medis,

                                 dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik AS r_jasa_pelayanan,

                                 dt_remun_detil.medis_dokter,

                                 ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100) as nominal_pajak,

                                 (dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100)) as sisa,

                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 DATE_FORMAT(dt_remun.awal,"%d %b %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %b %Y") as tgl_akhir,
                                 IF(dt_remun.id_bpjs IS NOT NULL,1,0) as jkn,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                 users.pajak,
                                 users.gapok,
                                 users.koreksi,
                                 users.foto,
                                 users.id_tenaga_bagian,
                                 users_tenaga_bagian.id_tenaga,
                                 users_tenaga_bagian.bagian,
                                 dt_ruang.ruang')
                    ->where('dt_remun_detil.id',$this->id)
                    ->first();

        if($detil->id_tenaga == 1){
        $jasa_real  = DB::table('dt_pasien_layanan_remun')
                        ->selectRaw('SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti_diterima,0)) +
                                     
                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima + dt_pasien_layanan_remun.jasa_operator_min,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi_diterima,0)) +

                                     SUM(IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr_diterima,0)) as total')

                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)
                        ->first(); 

        $total      = DB::table('dt_pasien_layanan_remun')
                        ->selectRaw('SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.tarif,0)) as tarif_claim,

                                     SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.'
                                     OR dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_tarif,0)) as tarif_real,
                                     
                                     SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_dpjp,0)) as real_dpjp,
                                     SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp,0)) as claim_dpjp,

                                     SUM(IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as dpjp,
                                     
                                     SUM(IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti,0)) as claim_pengganti,
                                     SUM(IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pengganti,0)) as real_pengganti,

                                     SUM(IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as pengganti,

                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_operator,0)) as real_operator,
                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator,0)) as claim_operator,
                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima,0)) as operator_diterima,
                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_min,0)) as min_operator,
                                     SUM(IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima + dt_pasien_layanan_remun.jasa_operator_min,0)) as operator,

                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as anastesi,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi,0)) as claim_anastesi,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_anastesi,0)) as real_anastesi,

                                     SUM(IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as pendamping,
                                     SUM(IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping,0)) as claim_pendamping,
                                     SUM(IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pendamping,0)) as real_pendamping,

                                     SUM(IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as konsul,
                                     SUM(IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul,0)) as claim_konsul,
                                     SUM(IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_konsul,0)) as real_konsul,

                                     SUM(IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as laborat,
                                     SUM(IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat,0)) as claim_laborat,
                                     SUM(IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_laborat,0)) as real_laborat,

                                     SUM(IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as tanggung,
                                     SUM(IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung,0)) as claim_tanggung,
                                     SUM(IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_tanggung,0)) as real_tanggung,

                                     SUM(IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as radiologi,
                                     SUM(IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi,0)) as claim_radiologi,
                                     SUM(IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_radiologi,0)) as real_radiologi,

                                     SUM(IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr_diterima,0)) * ('.$detil->r_medis.'/'.$jasa_real->total.') as rr,
                                     SUM(IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr,0)) as claim_rr,
                                     SUM(IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_rr,0)) as real_rr')

                        ->where('dt_pasien_layanan_remun.id_dpjp_real',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_pengganti',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_operator',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_anastesi',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_pendamping',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_konsul',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_laborat',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_tanggung',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_radiologi',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_rr',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)
                        ->first(); 

        $rincian    = DB::table('dt_pasien_layanan_remun')
                        ->leftjoin('dt_ruang','dt_pasien_layanan_remun.id_ruang_sub','=','dt_ruang.id')
                        ->leftjoin('dt_pasien','dt_pasien_layanan_remun.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_layanan_remun.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan_remun.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_pasien_layanan_remun.id,
                                     dt_ruang.ruang,
                                     dt_pasien.nama,
                                     dt_jasa.jasa,
                                     DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d %M %Y") as tanggal,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     dt_pasien_layanan_remun.real_tarif as tarif_real,
                                     dt_pasien_layanan_remun.tarif as tarif_claim,
                                     
                                     IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_dpjp,0) as real_dpjp,
                                     IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp,0) as claim_dpjp,
                                     IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_dpjp,
                                     
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pengganti,0) as real_pengganti,
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti,0) as claim_pengganti,
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_pengganti,
                                     
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_operator,0) as real_operator,
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator,0) as claim_operator,
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima,0) as jasa_operator_diterima,
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_min,0) as min_operator,
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima + dt_pasien_layanan_remun.jasa_operator_min,0) as jasa_operator,

                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_anastesi,0) as real_anastesi,
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi,0) as claim_anastesi,
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_anastesi,

                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pendamping,0) as real_pendamping,
                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping,0) as claim_pendamping,
                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_pendamping,

                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_konsul,0) as real_konsul,
                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul,0) as claim_konsul,
                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_konsul,

                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_laborat,0) as real_laborat,
                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat,0) as claim_laborat,
                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_laborat,

                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_tanggung,0) as real_tanggung,
                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung,0) as claim_tanggung,
                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_tanggung,

                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',dt_pasien_layanan_remun.real_jasa_radiologi,0) as real_radiologi,
                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',dt_pasien_layanan_remun.jasa_radiologi,0) as claim_radiologi,
                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_radiologi,

                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_rr,0) as real_rr,
                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr,0) as claim_rr,
                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr_diterima * ('.$detil->r_medis.'/'.$jasa_real->total.'),0) as jasa_rr,

                                     (IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp,0) +
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti,0) +
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator,0) +
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi,0) +
                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping,0) +
                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul,0) +
                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat,0) +
                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung,0) +
                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi,0) +
                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr,0)) as jasa_claim,

                                     (IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_dpjp,0) +
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pengganti,0) +
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_operator,0) +
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_anastesi,0) +
                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_pendamping,0) +
                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_konsul,0) +
                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_laborat,0) +
                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_tanggung,0) +
                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_radiologi,0) +
                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.real_jasa_rr,0)) as jasa_real,

                                     ((IF(dt_pasien_layanan_remun.id_dpjp_real = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_dpjp_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_pengganti = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pengganti_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_operator = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_operator_diterima + dt_pasien_layanan_remun.jasa_operator_min,0) +
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_pendamping = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_pendamping_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_konsul = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_konsul_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_laborat = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_laborat_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_tanggung = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_tanggung_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_radiologi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_radiologi_diterima,0) +
                                     IF(dt_pasien_layanan_remun.id_rr = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_rr_diterima,0))) * ('.$detil->r_medis.'/'.$jasa_real->total.') as jasa_medis')
                        
                        ->where('dt_pasien_layanan_remun.id_dpjp_real',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_pengganti',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_operator',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_anastesi',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_pendamping',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_konsul',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_laborat',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_tanggung',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_radiologi',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->orwhere('dt_pasien_layanan_remun.id_rr',$detil->id_karyawan)
                        ->whereNotNull('dt_pasien_layanan_remun.keluar')
                        ->whereDate('dt_pasien_layanan_remun.keluar','>=',$detil->awal)
                        ->whereDate('dt_pasien_layanan_remun.keluar','<=',$detil->akhir)
                        ->where('dt_pasien_layanan_remun.id_claim_bpjs',$detil->id_bpjs)
                        ->where('dt_pasien_layanan_remun.medis','>',0)
                        ->where('dt_pasien_layanan_remun.id_remun',$detil->id_remun)

                        ->get();               
      } else {
        $rincian = '';
        $total   = '';
        $jasa_real = '';
      }

        return view('export.remunerasi_rincian',compact('detil','rincian','total','jasa_real'));
    }
}