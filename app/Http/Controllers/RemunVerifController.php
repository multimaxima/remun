<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\dt_remun;
use App\dt_remun_detil;
use App\dt_pasien;
use App\dt_pasien_ruang;
use App\dt_pasien_layanan;
use App\Exports\RemunOlahExport;
use App\Exports\RemunRincian;
use App\Exports\RemunPembayaran;
use App\Exports\RemunKwitansi;
use Excel;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class RemunVerifController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function remunerasi_olah_data(){
      if(Auth::user()->id_akses == 6){
        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,
                                 dt_remun.a_jp,
                                 dt_remun_status.status,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jkn')
                    ->where('dt_remun.stat',3)
                    ->where('dt_remun.hapus',0)
                    ->orderby('dt_remun.id','desc')
                    ->get();
      }

      if(Auth::user()->id_akses == 7){
        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,
                                 dt_remun.a_jp,
                                 dt_remun_status.status,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jkn')
                    ->where('dt_remun.stat',4)
                    ->where('dt_remun.hapus',0)
                    ->orderby('dt_remun.id','desc')
                    ->get();
      }

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.remunerasi_olah_data',compact('remun'));
      } else {
        return view('remunerasi_olah_data',compact('remun'));
      }
    }

    public function remunerasi_spj_data(){
      if(Auth::user()->id_akses == 6){
      $remun  = DB::table('dt_remun')
                  ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                  ->selectRaw('dt_remun.id,
                               DATE_FORMAT(dt_remun.tanggal, "%W, %d %M %Y") as tanggal,
                               DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                               DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,
                               dt_remun.a_jp,
                               dt_remun_status.status,
                               IF(dt_remun.id_bpjs IS NOT NULL,"PASIEN BPJS","PASIEN UMUM") as jkn')
                  ->where('dt_remun.stat',5)
                  ->where('dt_remun.hapus',0)
                  ->orderby('dt_remun.id','desc')
                  ->get();

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.remunerasi_spj_data',compact('remun'));
      } else {
        return view('remunerasi_spj_data',compact('remun'));
      }

      } else {
        return back();
      }
    }

    public function remunerasi_olah(request $request){
        if($request->jenis){
        if($request->jenis == 1){
          $jenis = 1;
        }

        if($request->jenis == 2){
          $jenis = 0;
        }
      } else {
        $jenis  = '';
      }

      if($request->id_status){
        $id_status  = $request->id_status;
      } else {
        $id_status  = '';
      }

      if($request->id_bagian){
        $id_bagian  = $request->id_bagian;
      } else {
        $id_bagian  = '';
      }

      if($request->id_ruang){
        $id_ruang  = $request->id_ruang;
      } else {
        $id_ruang  = '';
      }

      $status     = DB::table('users_status')->get();
      $ruang      = DB::table('dt_ruang')
                      ->where('hapus',0)
                      ->orderby('ruang')
                      ->get();

      $bagian     = DB::table('users_tenaga_bagian')
                      ->where('hapus',0)
                      ->orderby('urut')
                      ->get();

        $remun  = DB::table('dt_remun')
                    ->where('dt_remun.id',Crypt::decrypt($request->id_remun))
                    ->selectRaw('dt_remun.id,
                                   dt_remun.tanggal,
                                   DATE_FORMAT(dt_remun.tanggal,"%d %M %Y") as tgl,
                                   dt_remun.id_bpjs,
                                   (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
                                   dt_remun.awal,
                                   DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                   DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                   dt_remun.akhir,
                                   DATE_FORMAT(dt_remun.jasa_awal,"%d %b %Y") as jasa_awal,
                                   DATE_FORMAT(dt_remun.jasa_akhir,"%d %b %Y") as jasa_akhir,
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

                                   dt_remun.stat,
                                   dt_remun.langkah')
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

                                    dt_remun_detil.total_indek,

                                    dt_remun_detil.medis,
                                    dt_remun_detil.titipan,
                                    dt_remun_detil.medis + dt_remun_detil.titipan as jumlah,

                                    dt_remun_detil.jasa_pelayanan,
                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,
                                    dt_remun_detil.r_total_indek,                                   
                                    dt_remun_detil.r_medis,
                                    dt_remun_detil.r_medis + dt_remun_detil.titipan as r_jumlah,
                                    dt_remun_detil.r_jasa_pelayanan,
                                    dt_remun_detil.nominal_pajak,
                                    dt_remun_detil.sisa,
                                    dt_remun_detil.perawat_setara,

                                    users.jabatan,
                                    users.id_ruang,
                                    users.staf,
                                    users.jp_admin,
                                    users_tenaga_bagian.id_tenaga,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gelar_depan,
                                    users.gelar_belakang,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.medis as kel_medis,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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

                                     SUM(dt_remun_detil.total_indek) AS total_indek,

                                     SUM(dt_remun_detil.medis) as medis,
                                     SUM(dt_remun_detil.titipan) as titipan,
                                     SUM(dt_remun_detil.medis + dt_remun_detil.titipan) as jumlah,

                                     SUM(dt_remun_detil.jasa_pelayanan) AS jasa_pelayanan,

                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil.r_total_indek) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.r_medis + dt_remun_detil.titipan) as r_jumlah,

                                     SUM(dt_remun_detil.r_jasa_pelayanan) AS r_jasa_pelayanan,

                                     SUM(dt_remun_detil.nominal_pajak) as nominal_pajak,

                                     SUM(dt_remun_detil.sisa) as sisa')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();

            $relokasi = DB::table('dt_remun_detil')
                          ->where('dt_remun_detil.id_remun',$remun->id)
                          ->selectRaw('SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik')
                          ->first();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_olah',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        } else {
            return view('remunerasi_olah',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        }
    }

    public function remunerasi_olah_kembali($id){      
      DB::table('dt_remun')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'stat' => 1,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->route('remunerasi_olah_data');
    }

    public function remunerasi_olah_spj(request $request){
        if($request->jenis){
        if($request->jenis == 1){
          $jenis = 1;
        }

        if($request->jenis == 2){
          $jenis = 0;
        }
      } else {
        $jenis  = '';
      }

      if($request->id_status){
        $id_status  = $request->id_status;
      } else {
        $id_status  = '';
      }

      if($request->id_bagian){
        $id_bagian  = $request->id_bagian;
      } else {
        $id_bagian  = '';
      }

      if($request->id_ruang){
        $id_ruang  = $request->id_ruang;
      } else {
        $id_ruang  = '';
      }

      $status     = DB::table('users_status')->get();
      $ruang      = DB::table('dt_ruang')
                      ->where('hapus',0)
                      ->orderby('ruang')
                      ->get();

      $bagian     = DB::table('users_tenaga_bagian')
                      ->where('hapus',0)
                      ->orderby('urut')
                      ->get();

        $remun  = DB::table('dt_remun')
                    ->where('dt_remun.id',Crypt::decrypt($request->id_remun))
                    ->selectRaw('dt_remun.id,
                                   dt_remun.tanggal,
                                   DATE_FORMAT(dt_remun.tanggal,"%d %M %Y") as tgl,
                                   dt_remun.id_bpjs,
                                   (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
                                   dt_remun.awal,
                                   DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                   DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                   dt_remun.akhir,
                                   DATE_FORMAT(dt_remun.jasa_awal,"%d %b %Y") as jasa_awal,
                                   DATE_FORMAT(dt_remun.jasa_akhir,"%d %b %Y") as jasa_akhir,
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

                                   dt_remun.stat,
                                   dt_remun.langkah')
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

                                    dt_remun_detil.total_indek,

                                    dt_remun_detil.medis as medis,
                                    dt_remun_detil.medis + dt_remun_detil.titipan as jumlah,

                                    dt_remun_detil.jasa_pelayanan,

                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,

                                    dt_remun_detil.r_total_indek,
                                    
                                    dt_remun_detil.r_medis,
                                    dt_remun_detil.titipan,
                                    dt_remun_detil.r_medis + dt_remun_detil.titipan as r_jumlah,

                                    dt_remun_detil.r_jasa_pelayanan,

                                    dt_remun_detil.nominal_pajak,

                                    dt_remun_detil.sisa,

                                    users.jabatan,
                                    users.id_ruang,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gelar_depan,
                                    users.gelar_belakang,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.medis as kel_medis,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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

                                     SUM(dt_remun_detil.total_indek) AS total_indek,

                                     SUM(dt_remun_detil.medis) as medis,
                                     SUM(dt_remun_detil.medis + dt_remun_detil.titipan) as jumlah,

                                     SUM(dt_remun_detil.jasa_pelayanan) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil.r_total_indek) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.titipan) as titipan,
                                     SUM(dt_remun_detil.r_medis + dt_remun_detil.titipan) as r_jumlah,

                                     SUM(dt_remun_detil.r_jasa_pelayanan) AS r_jasa_pelayanan,

                                     SUM(dt_remun_detil.nominal_pajak) as nominal_pajak,

                                     SUM(dt_remun_detil.sisa) as sisa')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();        

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_spj',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        } else {
            return view('remunerasi_spj',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        }
    }

    public function remunerasi_olah_ok($id){
      $remun  = DB::table('dt_remun')
                  ->where('id',Crypt::decrypt($id))
                  ->selectRaw('alokasi_apotik')
                  ->first();

      $detil  = DB::table('dt_remun_detil')
                  ->where('id_remun',Crypt::decrypt($id))
                  ->selectRaw('SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik')
                  ->first();

      if($remun->alokasi_apotik == $detil->alokasi_apotik){
        DB::table('dt_remun')
          ->where('id',Crypt::decrypt($id))
          ->update([
            'stat' => 4,
            'verif_data' => 1,
            'id_verif_data' => Auth::user()->id,
            'waktu_data' => now(),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->route('remunerasi_olah_data');
      } else {
        return back()->with('gagal','Masih terdapat sisa dana relokasi.');
      }    	
    }

    public function remunerasi_penyesuaian_non_perawat(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian')
                        ->where('users.id_tenaga_bagian',$request->id_bagian)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->first();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('users.id_tenaga_bagian',$request->id_bagian)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }        

        return back();
    }

    public function remunerasi_penyesuaian_interensif(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian')
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('users.id_tenaga_bagian','<>',24)
                        ->first();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('users.id_tenaga_bagian','<>',24)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }        

        return back();
    }

    public function remunerasi_penyesuaian_staf(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian')
                        ->where('users.id_tenaga_bagian',20)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->first();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('users.id_tenaga_bagian',20)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        return back();
    }

    public function remunerasi_penyesuaian_operator(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian')
                        ->where('users.id_tenaga_bagian',23)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->first();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('users.id_tenaga_bagian',23)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        return back();
    }

    public function remunerasi_penyesuaian_spesialis(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian')
                        ->where('users.id_tenaga_bagian',1)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->first();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('users.id_tenaga_bagian',1)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih * ($rinc->r_penyesuaian / $total->r_penyesuaian)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        return back();
    }

    public function remunerasi_penyesuaian_umum(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('dt_remun_detil.id')
                        ->where('users.id_tenaga_bagian',2)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->orwhere('users.id_tenaga_bagian',3)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->count();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('users.id_tenaga_bagian',2)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->orwhere('users.id_tenaga_bagian',3)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih  / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        return back();
    }

    public function remunerasi_penyesuaian_perawat(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('dt_remun_detil.id')
                        ->where('users_tenaga_bagian.kel_perawat',1)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->count();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('users_tenaga_bagian.kel_perawat',1)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        DB::table('dt_remun_detil')
            ->where('dt_remun_detil.id_remun',$data->id_remun)
            ->update([
                'rs_penyesuaian' => 0,
                'petugas_update' => Auth::user()->id,
            ]);

        return back();
    }

    public function remunerasi_penyesuaian_admin(request $request){
        DB::table('dt_remun_detil')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'rs_penyesuaian' => DB::raw('r_penyesuaian'),
                'r_penyesuaian' => str_replace(',','',$request->r_penyesuaian),
                'petugas_update' => Auth::user()->id,
            ]);

        $data       = DB::table('dt_remun_detil')
                        ->where('id',Crypt::decrypt($request->id))
                        ->first();

        $total      = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('dt_remun_detil.id')
                        ->where('users_tenaga_bagian.kel_perawat',0)
                        ->where('users_tenaga_bagian.administrasi',1)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->count();

        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_penyesuaian')
                        ->where('users_tenaga_bagian.kel_perawat',0)
                        ->where('users_tenaga_bagian.administrasi',1)
                        ->where('dt_remun_detil.id','<>',$data->id)
                        ->where('dt_remun_detil.id_remun',$data->id_remun)
                        ->where('dt_remun_detil.rs_penyesuaian',0)
                        ->get();

        #Pengurangan
        if($data->rs_penyesuaian > $data->r_penyesuaian){
            $selisih    = $data->rs_penyesuaian - $data->r_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian + ($selisih / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        if($data->rs_penyesuaian < $data->r_penyesuaian){
            $selisih    = $data->r_penyesuaian - $data->rs_penyesuaian;

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_penyesuaian' => $rinc->r_penyesuaian - ($selisih / $total),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }            
        }

        return back();
    }

    public function remunerasi_catatan(request $request){
    	DB::table('dt_remun_catatan')
    		->insert([
    			'id_users' => Auth::user()->id,
    			'id_remun' => $request->id_remun,
    			'catatan' => $request->catatan,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
    		]);

    	return back();
    }

    public function remunerasi_keuangan(request $request){
        $cek    = DB::table('control')->first();

        if($request->jenis){
            if($request->jenis == 1){
                $jenis = 1;
            }

            if($request->jenis == 2){
                $jenis = 0;
            }
        } else {
            $jenis  = '';
        }

        if($request->id_status){
            $id_status  = $request->id_status;
        } else {
            $id_status  = '';
        }

        if($request->id_ruang){
            $id_ruang  = $request->id_ruang;
        } else {
            $id_ruang  = '';
        }

        if($request->id_bagian){
            $id_bagian  = $request->id_bagian;
        } else {
            $id_bagian  = '';
        }

        $status     = DB::table('users_status')->get();
        $ruang      = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->orderby('ruang')
                        ->get();

        $bagian     = DB::table('users_tenaga_bagian')
                        ->where('kel_perawat',1)
                        ->where('hapus',0)
                        ->get();

        $bpjs   = DB::table('dt_claim_bpjs_stat')
                    ->where('stat',1)
                    ->where('hapus',0)
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 (SELECT SUM(dt_claim_bpjs.claim_jalan+dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim')
                    ->get();

        $remun  = DB::table('dt_remun')
                    ->where('stat',3)
                    ->where('hapus',0)
                    ->where('verif_keuangan',0)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 dt_remun.tpp,
                                 dt_remun.jp,
                                 dt_remun.penghasil,
                                 dt_remun.nonpenghasil,
                                 dt_remun.medis_perawat,
                                 dt_remun.admin,
                                 dt_remun.pos_remun,
                                 dt_remun.direksi,
                                 dt_remun.staf,
                                 dt_remun.verif_keuangan,
                                 DATE_FORMAT(dt_remun.waktu_keuangan, "%d %M %Y - %H:%i:%s") as waktu_keuangan,
                                 dt_remun.verif_kepegawaian,
                                 DATE_FORMAT(dt_remun.waktu_kepegawaian, "%d %M %Y - %H:%i:%s") as waktu_kepegawaian,
                                 dt_remun.verif_pelayanan,
                                 DATE_FORMAT(dt_remun.waktu_pelayanan, "%d %M %Y - %H:%i:%s") as waktu_pelayanan,
                                 dt_remun.kel_perawat,

                                 (dt_remun.admin +
                                 dt_remun.indek +
                                 dt_remun.tpp + 
                                 dt_remun.penyesuaian +
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
                                 dt_remun.r_direksi,
                                 dt_remun.r_staf,
                                 dt_remun.r_kel_perawat,

                                 (dt_remun.r_admin +
                                 dt_remun.r_indek +
                                 dt_remun.tpp +
                                 dt_remun.r_penyesuaian +
                                 dt_remun.r_direksi +
                                 dt_remun.r_staf +
                                 IFNULL(dt_remun.r_kel_perawat,0)) as r_indeks,

                                 dt_remun.stat')
                    ->first();

        if($remun){
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
                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi AS jasa_pelayanan,
                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,
                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,
                                    dt_remun_detil.r_medis,
                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi AS r_jasa_pelayanan,
                                    users.jabatan,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,
                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) AS r_jasa_pelayanan')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();

            $catatan 	= DB::table('dt_remun_catatan')
            				->leftjoin('users','dt_remun_catatan.id_users','=','users.id')
            				->where('dt_remun_catatan.id_remun',$remun->id)
            				->selectRaw('dt_remun_catatan.id,
            							       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
            							 dt_remun_catatan.catatan,
            							 DATE_FORMAT(dt_remun_catatan.created_at, "%d %M %Y - %H:%i:%s") as waktu')
            				->orderby('dt_remun_catatan.id','desc')
            				->get();
        } else {
            $rincian = '';
            $jumlah = '';
            $catatan = '';
        }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_olah',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','catatan'));
        } else {
            return view('remunerasi_olah',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','catatan'));
        }
    }

    public function remunerasi_keuangan_ok($id){
    	DB::table('dt_remun')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'verif_keuangan' => 1,
    			'waktu_keuangan' => now(),
          'petugas_update' => Auth::user()->id,
    		]);

    	return back();
    }

    public function remunerasi_kepegawaian(request $request){
        $cek    = DB::table('control')->first();

        if($request->jenis){
            if($request->jenis == 1){
                $jenis = 1;
            }

            if($request->jenis == 2){
                $jenis = 0;
            }
        } else {
            $jenis  = '';
        }

        if($request->id_status){
            $id_status  = $request->id_status;
        } else {
            $id_status  = '';
        }

        if($request->id_ruang){
            $id_ruang  = $request->id_ruang;
        } else {
            $id_ruang  = '';
        }

        if($request->id_bagian){
            $id_bagian  = $request->id_bagian;
        } else {
            $id_bagian  = '';
        }

        $status     = DB::table('users_status')->get();
        $ruang      = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->orderby('ruang')
                        ->get();

        $bagian     = DB::table('users_tenaga_bagian')
                        ->where('kel_perawat',1)
                        ->where('hapus',0)
                        ->get();

        $bpjs   = DB::table('dt_claim_bpjs_stat')
                    ->where('stat',1)
                    ->where('hapus',0)
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 (SELECT SUM(dt_claim_bpjs.claim_jalan+dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim')
                    ->get();

        $remun  = DB::table('dt_remun')
                    ->where('stat',3)
                    ->where('hapus',0)
                    ->where('verif_kepegawaian',0)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 dt_remun.tpp,
                                 dt_remun.jp,
                                 dt_remun.penghasil,
                                 dt_remun.nonpenghasil,
                                 dt_remun.medis_perawat,
                                 dt_remun.admin,
                                 dt_remun.pos_remun,
                                 dt_remun.direksi,
                                 dt_remun.staf,
                                 dt_remun.verif_keuangan,
                                 DATE_FORMAT(dt_remun.waktu_keuangan, "%d %M %Y - %H:%i:%s") as waktu_keuangan,
                                 dt_remun.verif_kepegawaian,
                                 DATE_FORMAT(dt_remun.waktu_kepegawaian, "%d %M %Y - %H:%i:%s") as waktu_kepegawaian,
                                 dt_remun.verif_pelayanan,
                                 DATE_FORMAT(dt_remun.waktu_pelayanan, "%d %M %Y - %H:%i:%s") as waktu_pelayanan,
                                 dt_remun.kel_perawat,

                                 (dt_remun.admin +
                                 dt_remun.indek +
                                 dt_remun.tpp + 
                                 dt_remun.penyesuaian +
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
                                 dt_remun.r_direksi,
                                 dt_remun.r_staf,
                                 dt_remun.r_kel_perawat,

                                 (dt_remun.r_admin +
                                 dt_remun.r_indek +
                                 dt_remun.tpp +
                                 dt_remun.r_penyesuaian +
                                 dt_remun.r_direksi +
                                 dt_remun.r_staf +
                                 IFNULL(dt_remun.r_kel_perawat,0)) as r_indeks,

                                 dt_remun.stat')
                    ->first();

        if($remun){
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
                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi AS jasa_pelayanan,
                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,
                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,
                                    dt_remun_detil.r_medis,
                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi AS r_jasa_pelayanan,
                                    users.jabatan,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,                                    
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,
                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) AS r_jasa_pelayanan')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();

            $catatan 	= DB::table('dt_remun_catatan')
            				->leftjoin('users','dt_remun_catatan.id_users','=','users.id')
            				->where('dt_remun_catatan.id_remun',$remun->id)
            				->selectRaw('dt_remun_catatan.id,
            							 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
            							 dt_remun_catatan.catatan,
            							 DATE_FORMAT(dt_remun_catatan.created_at, "%d %M %Y - %H:%i:%s") as waktu')
            				->orderby('dt_remun_catatan.id','desc')
            				->get();
        } else {
            $rincian = '';
            $jumlah = '';
            $catatan = '';
        }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_olah',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','catatan'));
        } else {
            return view('remunerasi_olah',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','catatan'));
        }
    }

    public function remunerasi_kepegawaian_ok($id){
    	DB::table('dt_remun')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'verif_kepegawaian' => 1,
    			'waktu_kepegawaian' => now(),
          'petugas_update' => Auth::user()->id,
    		]);

    	return back();
    }

    public function remunerasi_verif(request $request){
        if($request->jenis){
        if($request->jenis == 1){
          $jenis = 1;
        }

        if($request->jenis == 2){
          $jenis = 0;
        }
      } else {
        $jenis  = '';
      }

      if($request->id_status){
        $id_status  = $request->id_status;
      } else {
        $id_status  = '';
      }

      if($request->id_bagian){
        $id_bagian  = $request->id_bagian;
      } else {
        $id_bagian  = '';
      }

      if($request->id_ruang){
        $id_ruang  = $request->id_ruang;
      } else {
        $id_ruang  = '';
      }

      $tenaga     = DB::table('users_tenaga')
                      ->orderby('users_tenaga.id')
                      ->where('users_tenaga.id','<>',4)
                      ->where('users_tenaga.id','<>',5)
                      ->get();

      $status     = DB::table('users_status')->get();
      $ruang      = DB::table('dt_ruang')
                      ->where('hapus',0)
                      ->orderby('ruang')
                      ->get();

      $bagian     = DB::table('users_tenaga_bagian')
                      ->where('hapus',0)
                      ->orderby('urut')
                      ->get();

        $remun  = DB::table('dt_remun')
                    ->where('dt_remun.id',Crypt::decrypt($request->id_remun))
                    ->selectRaw('dt_remun.id,
                                   dt_remun.tanggal,
                                   DATE_FORMAT(dt_remun.tanggal,"%d %M %Y") as tgl,
                                   dt_remun.id_bpjs,
                                   (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
                                   dt_remun.awal,
                                   DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                   DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                   dt_remun.akhir,
                                   DATE_FORMAT(dt_remun.jasa_awal,"%d %b %Y") as jasa_awal,
                                   DATE_FORMAT(dt_remun.jasa_akhir,"%d %b %Y") as jasa_akhir,
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

                                   dt_remun.stat,
                                   dt_remun.langkah')
                    ->first();      

        if($request->id_ruang){
          $id_ruang  = $request->id_ruang;
          $ruangan   = DB::table('dt_ruang')->where('id',$request->id_ruang)->first();
          $apotik    = DB::table('dt_remun_detil')
                        ->selectRaw('SUM(dt_remun_detil.r_medis ) as jasa_apotik,
                                      SUM(dt_remun_detil.r_medis_asal ) as jasa_apotik_asal')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        ->where('dt_remun_detil.id_ruang',$request->id_ruang)
                        ->first();
        } else {
          $id_ruang  = '';
          $ruangan   = '';
          $apotik    = '';
        }

        if($request->id_tenaga){
          $id_tenaga   = $request->id_tenaga;
        } else {
          $id_tenaga   = '';
        }

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

                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi AS jasa_pelayanan,

                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,
                                    
                                    dt_remun_detil.r_medis,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi AS r_jasa_pelayanan,

                                    ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100) as nominal_pajak,

                                    (dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100)) as sisa,

                                    users.jabatan,
                                    users.id_ruang,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gelar_depan,
                                    users.gelar_belakang,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.medis as kel_medis,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) AS r_jasa_pelayanan,

                                     SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100)) as nominal_pajak,

                                    SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100))) as sisa')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();

            $relokasi = DB::table('dt_remun_detil')
                          ->where('dt_remun_detil.id_remun',$remun->id)
                          ->selectRaw('SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik')
                          ->first();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_verif',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','tenaga','id_tenaga'));
        } else {
            return view('remunerasi_verif',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','tenaga','id_tenaga'));
        }
    }

    public function remunerasi_verif_detil(request $request){
        if($request->id_ruang){
            $id_ruang  = $request->id_ruang;
        } else {
            $id_ruang  = '';
        }

        $status     = DB::table('users_status')->get();
        $ruang      = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->orderby('ruang')
                        ->get();

        $bagian     = DB::table('users_tenaga_bagian')
                        ->where('kel_perawat',1)
                        ->where('hapus',0)
                        ->get();

        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->where('dt_remun.stat','>',3)
                    ->where('dt_remun.stat',4)
                    ->where('dt_remun.hapus',0)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 DATE_FORMAT(dt_remun.tanggal,"%d %M %Y") as tgl,
                                 DATE_FORMAT(dt_remun.awal,"%d %b %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %b %Y") as tgl_akhir,
                                 dt_remun.id_bpjs,
                                 dt_remun.tpp,
                                 dt_remun.jp,
                                 dt_remun.penghasil,
                                 dt_remun.nonpenghasil,
                                 dt_remun.medis_perawat,
                                 dt_remun.admin,
                                 dt_remun.pos_remun,
                                 dt_remun.indek,
                                 dt_remun.penyesuaian,
                                 dt_remun.direksi,
                                 dt_remun.staf,
                                 dt_remun.kel_perawat,

                                 (dt_remun.admin +
                                 dt_remun.indek +
                                 dt_remun.tpp + 
                                 dt_remun.penyesuaian +
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
                                 dt_remun.r_penyesuaian,
                                 dt_remun.r_direksi,
                                 dt_remun.r_staf,
                                 dt_remun.r_kel_perawat,

                                 (dt_remun.r_admin +
                                 dt_remun.r_indek +
                                 dt_remun.tpp +
                                 dt_remun.r_penyesuaian +
                                 dt_remun.r_direksi +
                                 dt_remun.r_staf +
                                 IFNULL(dt_remun.r_kel_perawat,0)) as r_indeks,
                                 
                                 dt_remun.stat,
                                 dt_remun_status.status')
                    ->first();

        if($remun){
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
                                    dt_remun_detil.penyesuaian,
                                    dt_remun_detil.insentif_perawat,
                                    dt_remun_detil.direksi,
                                    dt_remun_detil.staf_direksi,
                                    dt_remun_detil.administrasi,

                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi AS total_indek,

                                    dt_remun_detil.medis as medis,

                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi AS jasa_pelayanan,

                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_penyesuaian,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,

                                    dt_remun_detil.r_medis,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi AS r_jasa_pelayanan,

                                    ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100) as nominal_pajak,

                                    (dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100)) as sisa,

                                    users.jabatan,
                                    users.id_ruang,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.medis as kel_medis,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',$remun->id)

                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

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
                                     SUM(dt_remun_detil.penyesuaian) as penyesuaian,
                                     SUM(dt_remun_detil.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil.direksi) as direksi,
                                     SUM(dt_remun_detil.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil.administrasi) as administrasi,

                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi) AS total_indek,

                                     SUM(dt_remun_detil.medis) as medis,
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) AS r_jasa_pelayanan,

                                     SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100)) as nominal_pajak,

                                    SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi) * dt_remun_detil.pajak / 100))) as sisa')
                        ->where('dt_remun_detil.id_remun',$remun->id)
                        
                        ->when($jenis, function ($query) use ($jenis) {
                            return $query->where('users_tenaga_bagian.medis',$jenis);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_status, function ($query) use ($id_status) {
                            return $query->where('users.id_status',$id_status);
                        })

                        ->first();
        } else {
            $rincian = '';
            $jumlah = '';
        }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_verif',compact('remun','rincian','jumlah','jenis','id_ruang','id_bagian','id_status','status','ruang','bagian'));
        } else {
            return view('remunerasi_verif',compact('remun','rincian','jumlah','jenis','id_ruang','id_bagian','id_status','status','ruang','bagian'));
        }
    }    

    public function remunerasi_verifikasi($id){
      $remun  = DB::table('dt_remun')
                  ->where('id',Crypt::decrypt($id))
                  ->selectRaw('alokasi_apotik')
                  ->first();

      $detil  = DB::table('dt_remun_detil')
                  ->where('id_remun',Crypt::decrypt($id))
                  ->selectRaw('SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik')
                  ->first();

      if($remun->alokasi_apotik == $detil->alokasi_apotik){
        DB::table('dt_remun')
          ->where('id',Crypt::decrypt($id))
          ->update([
            'stat' => 5,
            'verif_timremun' => 1,
            'id_verif_timremun' => Auth::user()->id,
            'waktu_timremun' => now(),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->route('remunerasi_olah_data');
      } else {
        Toastr::error('Total jasa tidak sesuai.');
        return back();
      }
    }

    public function remunerasi_tolak($id){
        DB::table('dt_remun')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'stat' => 3,
                'verif_data' => 0,
                'id_verif_data' => NULL,
                'waktu_data' => NULL,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->route('remunerasi_olah_data');
    }

    public function remunerasi_arsip($id){
        DB::table('dt_remun')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'stat' => 6,
                'petugas_update' => Auth::user()->id,
            ]);

        $cek  = DB::table('dt_remun')
                  ->where('id',Crypt::decrypt($id))
                  ->first();

        if($cek->id_pasien_jenis > 1){
          DB::select('CALL remun_finish('.$cek->id.');');
        }

        return redirect()->route('remunerasi_spj_data');
    }

    public function remunerasi_olah_cetak($id){       
        $remun  = DB::table('dt_remun')
                    ->where('dt_remun.id',Crypt::decrypt($id))
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                 dt_remun.id_bpjs,
                                 dt_remun.tpp,
                                 dt_remun.jp,
                                 dt_remun.penghasil,
                                 dt_remun.nonpenghasil,
                                 dt_remun.medis_perawat,
                                 dt_remun.admin,
                                 dt_remun.pos_remun,
                                 dt_remun.indek,
                                 dt_remun.penyesuaian,
                                 dt_remun.direksi,
                                 dt_remun.staf,
                                 dt_remun.kel_perawat,

                                 (dt_remun.admin +
                                 dt_remun.indek +
                                 dt_remun.tpp + 
                                 dt_remun.penyesuaian +
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
                                 dt_remun.r_penyesuaian,
                                 dt_remun.r_direksi,
                                 dt_remun.r_staf,
                                 dt_remun.r_kel_perawat,

                                 (dt_remun.r_admin +
                                 dt_remun.r_indek +
                                 dt_remun.tpp +
                                 dt_remun.r_penyesuaian +
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
                                    dt_remun_detil.penyesuaian,
                                    dt_remun_detil.insentif_perawat,
                                    dt_remun_detil.direksi,
                                    dt_remun_detil.staf_direksi,
                                    dt_remun_detil.administrasi,
                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi AS total_indek,
                                    dt_remun_detil.medis as medis,
                                    dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi AS jasa_pelayanan,
                                    dt_remun_detil.r_pos_remun,
                                    dt_remun_detil.r_indek,
                                    dt_remun_detil.r_penyesuaian,
                                    dt_remun_detil.r_insentif_perawat,
                                    dt_remun_detil.r_direksi,
                                    dt_remun_detil.r_staf_direksi,
                                    dt_remun_detil.r_administrasi,
                                    dt_remun_detil.alokasi_apotik,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik AS r_jasa_pelayanan,

                                    ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100) as nominal_pajak,

                                    (dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100)) as sisa,

                                    dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi AS r_total_indek,

                                    dt_remun_detil.r_medis,

                                    dt_remun_detil.alokasi_apotik + dt_remun_detil.r_medis as total_jasa,

                                    users.jabatan,
                                    users.id_ruang,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,                                    
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
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
                                     SUM(dt_remun_detil.penyesuaian) as penyesuaian,
                                     SUM(dt_remun_detil.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil.direksi) as direksi,
                                     SUM(dt_remun_detil.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil.administrasi) as administrasi,
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.direksi) AS total_indek,
                                     SUM(dt_remun_detil.medis) as medis,
                                     SUM(dt_remun_detil.pos_remun + dt_remun_detil.insentif_perawat + dt_remun_detil.penyesuaian + dt_remun_detil.staf_direksi + dt_remun_detil.administrasi + dt_remun_detil.medis + dt_remun_detil.direksi) AS jasa_pelayanan,
                                     SUM(dt_remun_detil.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil.r_indek) as r_indek,
                                     SUM(dt_remun_detil.r_penyesuaian) as r_penyesuaian,
                                     SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) AS r_jasa_pelayanan,

                                     SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100)) as nominal_pajak,

                                    SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) - 
                                    ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.alokasi_apotik) * dt_remun_detil.pajak / 100))) as sisa,

                                     SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_penyesuaian + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_direksi) AS r_total_indek,

                                     SUM(dt_remun_detil.r_medis) as r_medis,
                                     SUM(dt_remun_detil.r_medis + dt_remun_detil.alokasi_apotik) as total_jasa')
                        ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                        ->first();

        return view('remunerasi_olah_cetak',compact('remun','rincian','jumlah'));
    }

    public function remunerasi_olah_export($id){
        return Excel::download(new RemunOlahExport(Crypt::decrypt($id)), 'Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
