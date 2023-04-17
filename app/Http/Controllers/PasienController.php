<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\dt_pasien;
use App\dt_pasien_ruang;
use App\dt_pasien_layanan;
use App\dt_ruang;
use App\dt_ruang_jasa;
use App\User;
use App\Exports\PasienRincianPerDPJP;
use App\Exports\PasienPerDPJP;
use App\Exports\Pasien;
use Excel;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class PasienController extends Controller
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
    public function pasien(request $request){
        $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

        $ruang      = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->where('inap',1)
                        ->orwhere('hapus',0)
                        ->where('jalan',1)
                        ->orderby('ruang')
                        ->get();

        if($request->jns){
            $jns    = $request->jns;
        } else {
            $jns    = '';
        }        

        if($request->rng){
            $rng    = $request->rng;
        } else {
            $rng    = '';
        }

        if($request->cari){
            $cari    = $request->cari;
        } else {
            $cari    = '';
        }

        if($request->tampil){
            $tampil    = $request->tampil;
        } else {
            $tampil    = 10;
        }

        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_ruang','dt_pasien.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien.id,
                                    CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                    dt_ruang.ruang,
                                    dt_pasien.nama,
                                    dt_pasien.alamat,
                                    dt_pasien.no_mr,
                                    dt_pasien.alamat,                                    
                                    DATE_FORMAT(dt_pasien.masuk, "%d/%m/%Y") as masuk,
                                    dt_pasien_jenis.jenis as jenis_pasien')
                        ->whereNull('dt_pasien.keluar')
                        ->where('dt_pasien.hapus',0)

                        ->when($jns, function ($query) use ($jns) {
                            return $query->where('dt_pasien.id_jenis',$jns);
                        })

                        ->when($rng, function ($query) use ($rng) {
                            return $query->where('dt_pasien.id_ruang',$rng);
                        })

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%');
                        })

                        ->orderby('dt_pasien.nama')
                        ->paginate($tampil);  

        $agent = new Agent();

        if ($agent->isMobile()) {
            return view('mobile.pasien',compact('pasien','jenis','jns','ruang','rng','tampil','cari'));
        } else {
            return view('pasien',compact('pasien','jenis','jns','ruang','rng','tampil','cari'));
        }
    }

    public function pasien_export(request $request){
      return Excel::download(new Pasien($request->id_ruang, $request->id_jenis), 'Pasien Dalam Perawatan.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function pasien_detil(request $request){
        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                     DATE_FORMAT(dt_pasien.keluar, "%d/%m/%Y") as keluar,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     IFNULL(dt_pasien.umur_thn,0) as umur_thn,
                                     IFNULL(dt_pasien.umur_bln,0) as umur_bln,
                                     dt_pasien.no_mr,
                                     UPPER(dt_pasien_jenis.jenis) as jenis_pasien,
                                     (SELECT 
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      RIGHT OUTER JOIN dt_pasien_ruang ON (users.id = dt_pasien_ruang.id_dpjp)
                                      WHERE dt_pasien_ruang.id_pasien = dt_pasien.id
                                      LIMIT 1) as dpjp,
                                     dt_pasien.tagihan')
                        ->where('dt_pasien.id',Crypt::decrypt($request->id_pasien))
                        ->first();

        $ruang      = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     (SELECT 
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                      FROM users
                                      WHERE users.id = dt_pasien_ruang.id_dpjp_real) as dpjp,
                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                     (SELECT dt_ruang.ruang
                                      FROM dt_ruang
                                      WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,
                                     dt_jasa.jasa,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d %M %Y - %H:%i:%s") as waktu,
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
                                     dt_pasien_layanan.id_dpjp,                                     
                                     dt_pasien_layanan.n_dpjp,
                                     dt_pasien_layanan.jasa_dpjp,
                                     dt_pasien_layanan.n_pengganti,
                                     dt_pasien_layanan.jasa_pengganti,
                                     dt_pasien_layanan.n_operator,
                                     dt_pasien_layanan.jasa_operator,
                                     dt_pasien_layanan.n_anastesi,
                                     dt_pasien_layanan.jasa_anastesi,
                                     dt_pasien_layanan.n_pendamping,
                                     dt_pasien_layanan.jasa_pendamping,
                                     dt_pasien_layanan.n_konsul,
                                     dt_pasien_layanan.jasa_konsul,
                                     dt_pasien_layanan.n_laborat,
                                     dt_pasien_layanan.jasa_laborat,
                                     dt_pasien_layanan.n_tanggung,
                                     dt_pasien_layanan.jasa_tanggung,
                                     dt_pasien_layanan.n_radiologi,
                                     dt_pasien_layanan.jasa_radiologi,
                                     dt_pasien_layanan.n_rr,
                                     dt_pasien_layanan.jasa_rr,
                                     dt_pasien_layanan.medis,
                                     dt_pasien_layanan.n_jp_perawat,
                                     dt_pasien_layanan.jp_perawat,
                                     dt_pasien_layanan.n_pen_anastesi,
                                     dt_pasien_layanan.pen_anastesi,
                                     dt_pasien_layanan.n_per_asisten_1,
                                     dt_pasien_layanan.per_asisten_1,
                                     dt_pasien_layanan.n_per_asisten_2,
                                     dt_pasien_layanan.per_asisten_2,
                                     dt_pasien_layanan.n_instrumen,
                                     dt_pasien_layanan.instrumen,
                                     dt_pasien_layanan.n_sirkuler,
                                     dt_pasien_layanan.sirkuler,
                                     dt_pasien_layanan.n_per_pendamping_1,
                                     dt_pasien_layanan.per_pendamping_1,
                                     dt_pasien_layanan.n_per_pendamping_2,
                                     dt_pasien_layanan.per_pendamping_2,
                                     dt_pasien_layanan.n_apoteker,
                                     dt_pasien_layanan.apoteker,
                                     dt_pasien_layanan.n_ass_apoteker,
                                     dt_pasien_layanan.ass_apoteker,
                                     dt_pasien_layanan.n_admin_farmasi,
                                     dt_pasien_layanan.admin_farmasi,
                                     dt_pasien_layanan.n_administrasi,
                                     dt_pasien_layanan.administrasi,
                                     dt_pasien_layanan.n_pos_remun,
                                     dt_pasien_layanan.pos_remun,
                                     dt_pasien_layanan.n_direksi,
                                     dt_pasien_layanan.direksi,
                                     dt_pasien_layanan.n_staf_direksi,
                                     dt_pasien_layanan.staf_direksi,
                                     dt_pasien_layanan.n_insentif_perawat,
                                     dt_pasien_layanan.insentif_perawat,
                                     dt_pasien_layanan.n_pemulasaran,
                                     dt_pasien_layanan.pemulasaran')
                        ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                        ->get();

        $jns    = $request->jns;
        $rng    = $request->rng;

        $agent = new Agent();

        if ($agent->isMobile()) {
            return view('mobile.pasien_detil',compact('ruang','jns','pasien','rng'));
        } else {
            return view('pasien_detil',compact('ruang','jns','pasien','rng'));
        }
    }

    public function pasien_keluar(request $request){        
        if($request->cari){
            $cari    = $request->cari;
        } else {
            $cari    = '';
        }

        if($request->tampil){
            $tampil    = $request->tampil;
        } else {
            $tampil    = 10;
        }

        if($request->id_jenis){
            $id_jenis    = $request->id_jenis;
        } else {
            $id_jenis    = '';
        }

        if($request->id_dpjp){
            $id_dpjp    = $request->id_dpjp;
        } else {
            $id_dpjp    = '';
        }

        if($request->id_rawat){
            $id_rawat    = $request->id_rawat;
        } else {
            $id_rawat    = '';
        }

        if($request->awal){
            $awal    = $request->awal;
        } else {
            $awal    = date("Y-m-d");
        }

        if($request->akhir){
            $akhir    = $request->akhir;
        } else {
            $akhir    = date("Y-m-d");
        }

        $jenis  = DB::table('dt_pasien_jenis')                  
                  ->where('dt_pasien_jenis.hapus',0)
                  ->get();

        $rawat  = DB::table('dt_pasien_jenis_rawat')                  
                  ->where('dt_pasien_jenis_rawat.hapus',0)
                  ->get();

        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_pasien_jenis_rawat','dt_pasien.id_pasien_jenis_rawat','=','dt_pasien_jenis_rawat.id')
                        ->leftjoin('users','dt_pasien.id_dpjp','=','users.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                     DATE_FORMAT(dt_pasien.keluar, "%d/%m/%Y") as keluar,
                                     DATE_FORMAT(dt_pasien.masuk, "%d/%m/%Y") as masuk,
                                     DATEDIFF(dt_pasien.keluar,dt_pasien.masuk) as masa,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     dt_pasien.tagihan,
                                     dt_pasien.no_mr,
                                     dt_pasien.upp,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     dt_pasien_jenis_rawat.jenis_rawat,
                                     UPPER(dt_pasien_jenis.jenis) as jenis_pasien')
                        ->wheredate('dt_pasien.keluar','>=',$awal)
                        ->wheredate('dt_pasien.keluar','<=',$akhir)
                        ->where('dt_pasien.hapus',0)
                        ->where('dt_pasien.non_pasien',0)

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_pasien_jenis',$id_jenis);
                        })

                        ->when($id_rawat, function ($query) use ($id_rawat) {
                            return $query->where('dt_pasien.id_pasien_jenis_rawat',$id_rawat);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien.id_dpjp',$id_dpjp);
                        })

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%')
                                         ->orwhere('dt_pasien.no_mr',$cari);
                        })

                        ->orderby('dt_pasien.keluar')
                        ->paginate($tampil);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          return view('mobile.pasien_keluar',compact('pasien','awal','akhir','jenis','id_jenis','cari','tampil','id_dpjp','dpjp','rawat','id_rawat'));
        } else {
          return view('pasien_keluar',compact('pasien','awal','akhir','jenis','id_jenis','cari','tampil','id_dpjp','dpjp','rawat','id_rawat'));
        }
    }

    public function pasien_keluar_jenis(request $request){
      $data   = DB::table('dt_pasien')
                  ->where('id',$request->id)
                  ->first();

      echo json_encode($data);
    }

    public function pasien_keluar_jenis_simpan(request $request){
      DB::table('dt_pasien')
        ->where('id',$request->id)
        ->update([
          'id_pasien_jenis' => $request->id_jenis,
        ]);

      return back();
    }

    public function pasien_keluar_dpjp(request $request){
      $data   = DB::table('dt_pasien')
                  ->where('id',$request->id)
                  ->first();

      echo json_encode($data);
    }

    public function pasien_keluar_dpjp_simpan(request $request){
      DB::table('dt_pasien_ruang')
        ->where('id_pasien',$request->id)
        ->update([
          'id_dpjp' => $request->id_dpjp,
        ]);

      return back();
    }

    public function pasien_keluar_detil(request $request){
        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                     DATE_FORMAT(dt_pasien.keluar, "%d/%m/%Y") as keluar,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     CONCAT(IF(dt_pasien.umur IS NULL,"",CONCAT(dt_pasien.umur," Tahun ")),
                                     IF(dt_pasien.umur_bln IS NULL,"",CONCAT(dt_pasien.umur_bln," Bulan"))) as umur,
                                     dt_pasien.no_mr,
                                     UPPER(dt_pasien_jenis.jenis) as jenis_pasien,
                                     (SELECT SUM(dt_pasien_layanan.tarif)  
                                      FROM dt_pasien_layanan
                                      WHERE dt_pasien_layanan.id_pasien_layanan = dt_pasien.id) as tagihan')
                        ->where('dt_pasien.id',Crypt::decrypt($request->id_pasien))
                        ->first();

        $ruang      = DB::table('dt_pasien_ruang')
                        ->leftjoin('users','pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_ruang','pasien_ruang.id_ruang','=','dt_ruang.id')                            
                        ->selectRaw('pasien_ruang.id,
                                     DATE_FORMAT(pasien_ruang.masuk, "%d %M %Y - %H:%i:%s") as masuk,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) AS dpjp,                                         
                                     dt_ruang.ruang')
                        ->where('pasien_ruang.id_pasien',$pasien->id)
                        ->get();

        $layanan    = DB::table('pasien_layanan')
                        ->leftjoin('dt_jasa','pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->selectRaw('pasien_layanan.id,
                                        pasien_layanan.id_pasien_ruang,
                                        dt_jasa.jasa,
                                        pasien_layanan.tarif')
                        ->where('pasien_layanan.id_pasien',$pasien->id)
                        ->get();

        $layanan_1  = DB::table('pasien_layanan_1')
                        ->leftjoin('dt_rekening_perhitungan','pasien_layanan_1.id_rekening','=','dt_rekening_perhitungan.id')
                        ->selectRaw('pasien_layanan_1.id,
                                     pasien_layanan_1.id_layanan,
                                     dt_rekening_perhitungan.nama,
                                     pasien_layanan_1.nominal')
                        ->where('pasien_layanan_1.id_pasien',$pasien->id)
                        ->get();

        $layanan_2  = DB::table('pasien_layanan_2')
                        ->leftjoin('dt_rekening_perhitungan','pasien_layanan_2.id_rekening','=','dt_rekening_perhitungan.id')
                        ->selectRaw('pasien_layanan_2.id,
                                     pasien_layanan_2.id_layanan_1,
                                     dt_rekening_perhitungan.nama,
                                     pasien_layanan_2.nominal')
                        ->where('pasien_layanan_2.id_pasien',$pasien->id)
                        ->get();        

        $awal   = $request->awal;
        $akhir  = $request->akhir;
        $jns    = $request->jns;

        return view('pasien_keluar_detil',compact('ruang','awal','akhir','jns','layanan','pasien','layanan_1','layanan_2'));
    }        

    public function pasien_ruang(request $request){
      if($request->cari){
          $cari   = $request->cari;
      } else {
          $cari   = '';
      }

      if($request->pasienku){
          $pasienku   = $request->pasienku;
      } else {
          $pasienku   = 0;
      }

      $pasien     = DB::table('dt_pasien_ruang')
                      ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                      ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                      ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                      ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                      ->selectRaw('dt_pasien.id AS id_pasien,
                                   CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                   dt_pasien.umur_thn,
                                   dt_pasien.umur_bln,
                                   dt_pasien.nama,                                   
                                   dt_pasien.no_mr,
                                   dt_pasien_jenis.jenis as jenis_pasien,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) AS dpjp,
                                   dt_pasien_ruang.id AS id_pasien_ruang,
                                   dt_pasien.id_ruang,
                                   dt_ruang.ruang')
                      ->where('dt_pasien_ruang.stat',0)
                      ->where('dt_pasien.hapus',0)

                      ->when($pasienku == 0, function ($query) use ($pasienku) {
                          return $query->where('dt_pasien_ruang.id_ruang',Auth::user()->id_ruang);
                        })

                      ->when($cari, function ($query) use ($cari) {
                          return $query->where('dt_pasien.nama','like','%'.$cari.'%')
                                       ->where('dt_pasien_ruang.stat',0)
                                       ->orwhere('dt_pasien.no_mr',$cari)
                                       ->where('dt_pasien_ruang.stat',0);
                        })

                      ->orderby('dt_pasien.nama')
                      ->get();

      if($request->id_pasien){
        $cek    = DB::table('dt_pasien')
                    ->where('id',Crypt::decrypt($request->id_pasien))
                    ->where('stat',0)
                    ->where('hapus',0)
                    ->count();

        if($cek > 0){
          $pass       = DB::table('dt_pasien_ruang')
                          ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                          ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                          ->where('dt_pasien.id',Crypt::decrypt($request->id_pasien))
                          ->where('dt_pasien_ruang.stat',0)
                          ->selectRaw('dt_pasien.id,
                                       CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                       dt_pasien.no_mr,
                                       dt_pasien.nama,
                                       dt_pasien.alamat,                                         
                                       dt_pasien.umur_thn,
                                       dt_pasien.umur_bln,
                                       dt_pasien.id_kelamin,
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                       dt_pasien.id_ruang,
                                       (SELECT dt_ruang.ruang
                                        FROM dt_ruang
                                        WHERE dt_ruang.id = dt_pasien.id_ruang) as ruang,
                                       dt_pasien_ruang.id_dpjp,
                                       dt_pasien_ruang.id_pasien_jenis,
                                       dt_pasien_ruang.id_pasien_jenis_rawat,
                                       dt_pasien_ruang.id as id_pasien_ruang,
                                       dt_pasien_jenis.jenis as jenis_pasien')
                          ->first();

          $total      = DB::table('dt_pasien_layanan')
                          ->where('id_pasien',$pass->id)
                          ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif')
                          ->first();

          $ruang      = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->where('dt_pasien_layanan.id_pasien',Crypt::decrypt($request->id_pasien))
                          ->selectRaw('dt_pasien_layanan.id,
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users WHERE users.id = dt_pasien_layanan.id_dpjp_real) as nama,

                                       (SELECT dt_ruang.ruang
                                        FROM dt_ruang
                                        WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,

                                       (SELECT dt_ruang.ruang
                                        FROM dt_ruang
                                        WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,

                                       dt_pasien_layanan.id_pasien_ruang,
                                       dt_pasien_layanan.id_ruang_sub,
                                       DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                       dt_pasien_layanan.id_jasa,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,

                                       (SELECT dt_jasa.jasa
                                        FROM dt_jasa
                                        WHERE dt_jasa.id = dt_pasien_layanan.id_jasa) as jasa,
                                         
                                       dt_pasien_layanan.tarif')
                          ->orderby('dt_pasien_layanan.waktu')
                          ->get();            

        } else {
          $pass       = '';
          $ruang      = '';
          $total      = '';
        }
      } else {
        $pass       = '';
        $ruang      = '';
        $total      = '';
      }

      $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

      $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();

      $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users.id_ruang',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_2',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      if(count($dpjp) == 0){
        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();
      }

      $dpjp_lain   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      $dpjp_anastesi = DB::table('users')
                    ->where('users.id_ruang',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_2',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      $d_ruang    = DB::table('dt_ruang')
                      ->where('dt_ruang.jalan',1)
                      ->where('dt_ruang.hapus',0)
                      ->where('id','<>',Auth::user()->id_ruang)

                      ->orwhere('dt_ruang.inap',1)
                      ->where('dt_ruang.hapus',0)
                      ->where('id','<>',Auth::user()->id_ruang)                        

                      ->orderby('ruang')
                      ->get();

      $jasa       = DB::table('dt_ruang_jasa')
                      ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                      ->selectRaw('dt_ruang_jasa.id,
                                   dt_ruang_jasa.id_ruang,
                                   dt_ruang_jasa.id_jasa,
                                   dt_jasa.jasa')
                      ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                      ->where('dt_ruang_jasa.hapus',0)
                      ->orderby('dt_jasa.jasa')
                      ->get();        

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.pasien_ruang',compact('pasien','pass','ruang','jenis','dpjp','d_ruang','jasa','cari','pasienku','dpjp_lain','dpjp_anastesi','total'));
      } else {
        return view('pasien_ruang',compact('pasien','pass','ruang','jenis','dpjp','d_ruang','jasa','cari','pasienku','dpjp_lain','dpjp_anastesi','total'));
      }
    }    

    public function pasien_ruang_mobile(request $request){
      $id_pasien = $request->id_pasien;
      $cari = $request->cari;
      $pasienku = $request->pasienku;

      $pass       = DB::table('dt_pasien_ruang')
                      ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                      ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                      ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                      ->where('dt_pasien.id',$id_pasien)
                      ->where('dt_pasien_ruang.stat',0)
                      ->selectRaw('dt_pasien.id,
                                   CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                   dt_pasien.no_mr,
                                   dt_pasien.nama,
                                   dt_pasien.alamat,                                         
                                   dt_pasien.umur_thn,
                                   dt_pasien.umur_bln,
                                   dt_pasien.id_kelamin,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                   dt_pasien.id_ruang,
                                   (SELECT dt_ruang.ruang
                                    FROM dt_ruang
                                    WHERE dt_ruang.id = dt_pasien.id_ruang) as ruang,
                                   dt_pasien_ruang.id_dpjp,
                                   dt_pasien_ruang.id_pasien_jenis,
                                   dt_pasien_ruang.id_pasien_jenis_rawat,
                                   dt_pasien_ruang.id as id_pasien_ruang,
                                   dt_pasien_jenis.jenis as jenis_pasien')
                      ->first();

      $total      = DB::table('dt_pasien_layanan')
                      ->where('id_pasien',$pass->id)
                      ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif')
                      ->first();

      $ruang      = DB::table('dt_pasien_layanan')
                      ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                      ->where('dt_pasien_layanan.id_pasien',$id_pasien)
                      ->selectRaw('dt_pasien_layanan.id,
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users WHERE users.id = dt_pasien_layanan.id_dpjp_real) as nama,

                                       (SELECT dt_ruang.ruang
                                        FROM dt_ruang
                                        WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,

                                       (SELECT dt_ruang.ruang
                                        FROM dt_ruang
                                        WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,

                                       dt_pasien_layanan.id_pasien_ruang,
                                       dt_pasien_layanan.id_ruang_sub,
                                       DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                       dt_pasien_layanan.id_jasa,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,
                                         
                                       (SELECT 
                                        CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                        IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                        FROM users 
                                        WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,

                                       (SELECT dt_jasa.jasa
                                        FROM dt_jasa
                                        WHERE dt_jasa.id = dt_pasien_layanan.id_jasa) as jasa,
                                         
                                       dt_pasien_layanan.tarif')
                          ->orderby('dt_pasien_layanan.waktu')
                          ->get();   

      $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

      $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();

      $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users.id_ruang',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_2',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      if(count($dpjp) == 0){
        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();
      }

      $dpjp_lain   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      $d_ruang    = DB::table('dt_ruang')
                      ->where('dt_ruang.jalan',1)
                      ->where('dt_ruang.hapus',0)
                      ->where('id','<>',Auth::user()->id_ruang)

                      ->orwhere('dt_ruang.inap',1)
                      ->where('dt_ruang.hapus',0)
                      ->where('id','<>',Auth::user()->id_ruang)                        

                      ->orderby('ruang')
                      ->get();

      $jasa       = DB::table('dt_ruang_jasa')
                      ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                      ->selectRaw('dt_ruang_jasa.id,
                                   dt_ruang_jasa.id_ruang,
                                   dt_ruang_jasa.id_jasa,
                                   dt_jasa.jasa')
                      ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                      ->where('dt_ruang_jasa.hapus',0)
                      ->orderby('dt_jasa.jasa')
                      ->get();

      return view('mobile.pasien_ruang_detil',compact('pass','ruang','jenis','dpjp','d_ruang','jasa','cari','pasienku','dpjp_lain','total','id_pasien'));
    }

    public function pasien_ruang_data(request $request){
      if($request->cari){
        $cari = $request->cari;
      } else {
        $cari = '';
      }

      if($request->tampil){
        $tampil = $request->tampil;
      } else {
        $tampil = 10;
      }

      if($request->awal){
        $awal = $request->awal;
      } else {
        $awal = date('Y-m-d');
      }

      if($request->akhir){
        $akhir = $request->akhir;
      } else {
        $akhir = date('Y-m-d');
      }

      if($request->id_pasien){
        $id_pasien = $request->id_pasien;
      } else {
        $id_pasien = '';
      }

      if($request->id_jenis){
        $id_jenis = $request->id_jenis;
      } else {
        $id_jenis = '';
      }

      $pasien   = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien.nama,
                                 dt_pasien.no_mr,     
                                 dt_pasien_layanan.id_pasien,                            
                                 CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                 DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y -  %H:%i") as waktu,
                                 dt_pasien_jenis.jenis as jenis_pasien,
                                 dt_jasa.jasa,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                 dt_pasien_layanan.tarif,                                 
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp,
                                 dt_pasien_layanan.jasa_dpjp,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,
                                 dt_pasien_layanan.jasa_pengganti,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                 dt_pasien_layanan.jasa_operator,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,
                                 dt_pasien_layanan.jasa_anastesi,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,
                                 dt_pasien_layanan.jasa_pendamping,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                 dt_pasien_layanan.jasa_konsul,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_laborat) as laborat,
                                 dt_pasien_layanan.jasa_laborat,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung,
                                 dt_pasien_layanan.jasa_tanggung,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_radiologi) as radiologi,
                                 dt_pasien_layanan.jasa_radiologi,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) 
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
                                 dt_pasien_layanan.admin_farmasi,
                                 dt_pasien_layanan.administrasi,                                 
                                 dt_pasien_layanan.pemulasaran,
                                 dt_pasien_layanan.fisio')
                    ->where('dt_pasien_layanan.id_ruang',Auth::user()->id_ruang)
                    ->when($id_pasien, function ($query) use ($id_pasien) {
                            return $query->where('dt_pasien_layanan.id_pasien','=',$id_pasien);
                        })
                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis','=',$id_jenis);
                        })
                    ->when($awal, function ($query) use ($awal) {
                            return $query->whereDate('dt_pasien_layanan.waktu','>=',$awal);
                        })
                    ->when($akhir, function ($query) use ($akhir) {
                            return $query->whereDate('dt_pasien_layanan.waktu','<=',$akhir);
                        })
                    ->when($cari, function ($query) use ($cari) {
                            return $query->whereDate('dt_pasien.nama','LIKE','%'.$cari.'%');
                        })
                    ->orderby('dt_pasien.nama')
                    ->paginate($tampil);

      $total    = DB::table('dt_pasien_layanan')                    
                    ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif,
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
                                 SUM(dt_pasien_layanan.admin_farmasi) as admin_farmasi,
                                 SUM(dt_pasien_layanan.administrasi) as administrasi,                                 
                                 SUM(dt_pasien_layanan.pemulasaran) as pemulasaran,
                                 SUM(dt_pasien_layanan.fisio) as fisio')

                    ->where('dt_pasien_layanan.id_ruang',Auth::user()->id_ruang)
                    ->when($id_pasien, function ($query) use ($id_pasien) {
                            return $query->where('dt_pasien_layanan.id_pasien','=',$id_pasien);
                        })
                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis','=',$id_jenis);
                        })
                    ->when($awal, function ($query) use ($awal) {
                            return $query->whereDate('dt_pasien_layanan.waktu','>=',$awal);
                        })
                    ->when($akhir, function ($query) use ($akhir) {
                            return $query->whereDate('dt_pasien_layanan.waktu','<=',$akhir);
                        })
                    ->when($cari, function ($query) use ($cari) {
                            return $query->whereDate('dt_pasien.nama','LIKE','%'.$cari.'%');
                        })
                    ->first();

      $jenis  = DB::table('dt_pasien_jenis')
                  ->where('dt_pasien_jenis.hapus',0)
                  ->get();

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.pasien_ruang_data',compact('awal','akhir','pasien','id_pasien','jenis','id_jenis','total','cari','tampil'));
      } else {
        return view('pasien_ruang_data',compact('awal','akhir','pasien','id_pasien','jenis','id_jenis','total','cari','tampil'));
      }      
    }

    public function pasien_ruang_data_cetak(request $request){
      if($request->awal){
        $awal = $request->awal;
      } else {
        $awal = date('Y-m-d');
      }

      if($request->akhir){
        $akhir = $request->akhir;
      } else {
        $akhir = date('Y-m-d');
      }

      if($request->id_pasien){
        $id_pasien = $request->id_pasien;
      } else {
        $id_pasien = '';
      }

      if($request->id_jenis){
        $id_jenis = $request->id_jenis;
      } else {
        $id_jenis = '';
      }

      $tgl_awal   = Carbon::parse($awal)->format('d F Y','id');
      $tgl_akhir  = Carbon::parse($akhir)->format('d F Y','id');

      $pasien   = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien.nama,
                                 dt_pasien.no_mr,     
                                 dt_pasien_layanan.id_pasien,                            
                                 CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                 DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y -  %H:%i") as waktu,
                                 dt_pasien_jenis.jenis as jenis_pasien,
                                 dt_jasa.jasa,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                 dt_pasien_layanan.tarif,                                 
                                 dt_pasien_layanan.jasa_dpjp,
                                 dt_pasien_layanan.jasa_pengganti,
                                 dt_pasien_layanan.jasa_operator,
                                 dt_pasien_layanan.jasa_anastesi,
                                 dt_pasien_layanan.jasa_pendamping,
                                 dt_pasien_layanan.jasa_konsul,
                                 dt_pasien_layanan.jasa_laborat,
                                 dt_pasien_layanan.jasa_tanggung,
                                 dt_pasien_layanan.jasa_radiologi,
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
                                 dt_pasien_layanan.admin_farmasi,
                                 dt_pasien_layanan.administrasi,                                 
                                 dt_pasien_layanan.pemulasaran,
                                 dt_pasien_layanan.fisio')
                    ->where('dt_pasien_layanan.id_ruang',Auth::user()->id_ruang)
                    ->when($id_pasien, function ($query) use ($id_pasien) {
                            return $query->where('dt_pasien_layanan.id_pasien','=',$id_pasien);
                        })
                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis','=',$id_jenis);
                        })
                    ->when($awal, function ($query) use ($awal) {
                            return $query->whereDate('dt_pasien_layanan.waktu','>=',$awal);
                        })
                    ->when($akhir, function ($query) use ($akhir) {
                            return $query->whereDate('dt_pasien_layanan.waktu','<=',$akhir);
                        })
                    ->orderby('dt_pasien.nama')
                    ->get();

      $total    = DB::table('dt_pasien_layanan')                    
                    ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif,
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
                                 SUM(dt_pasien_layanan.admin_farmasi) as admin_farmasi,
                                 SUM(dt_pasien_layanan.administrasi) as administrasi,                                 
                                 SUM(dt_pasien_layanan.pemulasaran) as pemulasaran,
                                 SUM(dt_pasien_layanan.fisio) as fisio')

                    ->where('dt_pasien_layanan.id_ruang',Auth::user()->id_ruang)
                    ->when($id_pasien, function ($query) use ($id_pasien) {
                            return $query->where('dt_pasien_layanan.id_pasien','=',$id_pasien);
                        })
                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis','=',$id_jenis);
                        })
                    ->when($awal, function ($query) use ($awal) {
                            return $query->whereDate('dt_pasien_layanan.waktu','>=',$awal);
                        })
                    ->when($akhir, function ($query) use ($akhir) {
                            return $query->whereDate('dt_pasien_layanan.waktu','<=',$akhir);
                        })
                    ->first();

      $ruang  = DB::table('dt_ruang')
                  ->where('id',Auth::user()->id_ruang)
                  ->first();

      return view('pasien_ruang_data_cetak',compact('awal','akhir','pasien','ruang','tgl_awal','tgl_akhir','total'));
    }

    public function pasien_ganti_dpjp(request $request){
      DB::table('dt_pasien')
        ->where('id',$request->id_pasien)
        ->update([
          'id_dpjp' => $request->id_dpjp,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_pasien_ruang')
        ->where('id',$request->id_pasien_ruang)
        ->update([
          'id_dpjp' => $request->id_dpjp,
          'id_dpjp_real' => $request->id_dpjp_real,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_pasien_layanan')
        ->where('id_pasien',$request->id_pasien)
        ->update([
          'id_dpjp' => $request->id_dpjp,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_pasien_layanan')
        ->where('id_pasien_ruang',$request->id_pasien_ruang)
        ->whereNull('id_dpjp_real')
        ->update([
          'id_dpjp_real' => $request->id_dpjp,
          'petugas_update' => Auth::user()->id,
        ]);

      return back();
    }

    public function pasien_edit_ruang(request $request){
      DB::table('dt_pasien')
        ->where('dt_pasien.id',Crypt::decrypt($request->id))
        ->update([
          'nama' => $request->nama,
          'no_mr' => $request->no_mr,
          'alamat' => $request->alamat,
          'umur_thn' => $request->umur_thn,
          'umur_bln' => $request->umur_bln,
          'id_kelamin' => $request->id_kelamin,
          'petugas_update' => Auth::user()->id,
        ]);

      return back();
    }

    public function pasien_ubah_status(request $request){      
      DB::select('CALL ubah_status('.$request->id.','.$request->jenis.');');
      //return response()->json(['return' => 'some data']);
      return back();
    }

    public function pasien_ruang_detil($id){
        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $d_ruang    = DB::table('dt_ruang')
                        ->where('dt_ruang.jalan',1)
                        ->where('dt_ruang.hapus',0)
                        ->where('id','<>',Auth::user()->id_ruang)

                        ->orwhere('dt_ruang.inap',1)
                        ->where('dt_ruang.hapus',0)
                        ->where('id','<>',Auth::user()->id_ruang)                        

                        ->orderby('ruang')
                        ->get();

        $jasa       = DB::table('dt_ruang_jasa')
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->orderby('dt_jasa.jasa')
                        ->get();        

        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->selectRaw('dt_pasien.id AS id_pasien,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien.tagihan,
                                     dt_pasien.tgl_data,
                                     dt_pasien.nama,                                   
                                     dt_pasien.no_mr,
                                     dt_pasien.stat,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) AS dpjp,
                                     dt_pasien_ruang.id AS id_pasien_ruang,
                                     dt_pasien.id_ruang,
                                     dt_pasien_ruang.id_dpjp,
                                     dt_pasien_ruang.id_jenis')
                        ->where('dt_pasien_ruang.id',Crypt::decrypt($id))
                        ->first();

        $ruang      = dt_pasien_ruang::leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->where('dt_pasien_ruang.id_pasien',$pasien->id_pasien)
                            ->selectRaw('dt_pasien_ruang.id,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_ruang.ruang')
                            ->with(array(
                                'pasien_layanan' => function($layanan) {
                                    $layanan->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                        ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d %b %Y - %H:%i:%s") as waktu,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak,
                                         dt_pasien_layanan.id_jasa,
                                         IF(dt_pasien_layanan.id_jasa = 3,
                                         (SELECT 
                                          CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                          IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users WHERE users.id = dt_pasien_layanan.id_konsul),"") as konsul,
                                         dt_jasa.jasa,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,     
                                         dt_pasien_layanan.tarif')
                                        ->orderby('dt_pasien_layanan.waktu');
                                },
                            ))
                            ->orderby('dt_pasien_ruang.id','desc')
                            ->get();

        return view('mobile.pasien_ruang_detil',compact('pasien','ruang','dpjp','d_ruang','jasa'));
    }

    public function pasien_layanan_hapus($id){
        DB::table('dt_pasien_layanan')
          ->where('id',Crypt::decrypt($id))
          ->delete();

        return back();
    }

    public function pasien_baru_form(request $request){
      $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

      $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();

      $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users.id_ruang',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',Auth::user()->id_ruang)
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      if(count($dpjp) == 0){
        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();
      }

      return view('pasien_baru',compact('jenis','dpjp'));
    }

    public function pasien_baru(request $request){
        DB::table('dt_pasien')
            ->insert([
                'nama' => $request->nama,
                'alamat' => $request->alamat,                
                'umur_thn' => $request->umur_thn,
                'umur_bln' => $request->umur_bln,
                'id_kelamin' => $request->id_kelamin,
                'no_mr' => $request->no_mr,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'id_petugas' => Auth::user()->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_dpjp' => $request->id_dpjp,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        $baru   = DB::table('dt_pasien')->where('id_petugas',Auth::user()->id)->orderby('id','desc')->first();

        DB::table('dt_pasien_ruang')
            ->insert([
                'id_pasien' => $baru->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_pasien_jenis' => $baru->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $baru->id_pasien_jenis_rawat,
                'masuk' => now(),
                'id_petugas' => Auth::user()->id,
                'id_dpjp' => $request->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);        

        return redirect()->route('pasien_ruang',['id_pasien' => Crypt::encrypt($baru->id)]);
    }

    public function pasien_edit(request $request){
        DB::table('pasien')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'dusun' => $request->dusun,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'no_prop_edit' => $request->no_prop_edit,
                'no_kab_edit' => $request->no_kab_edit,
                'no_kec_edit' => $request->no_kec_edit,
                'no_kel_edit' => $request->no_kel_edit,
                'temp_lahir' => $request->temp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'no_mr' => $request->no_mr,
                'id_jenis' => $request->id_jenis,
                'petugas_update' => Auth::user()->id,
            ]);        

        return back();
    }

    public function pasien_pulang($id){
        $ruang  = DB::table('dt_pasien_ruang')
                    ->where('dt_pasien_ruang.id_pasien',Crypt::decrypt($id))
                    ->where('dt_pasien_ruang.stat',0)
                    ->first();

        DB::table('dt_pasien_ruang')
          ->where('id_pasien',Crypt::decrypt($id))
          ->update([
            'stat' => 1,
            'keluar' => now(),
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien_layanan')
          ->where('dt_pasien_layanan.id_pasien',Crypt::decrypt($id))
          ->update([
            'id_dpjp' => $ruang->id_dpjp,
            'keluar' => date("Y-m-d"),
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien_layanan')
          ->where('id_pasien_ruang',$ruang->id)
          ->whereNull('id_dpjp_real')
          ->update([
            'id_dpjp_real' => $ruang->id_dpjp,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien')
          ->where('id',Crypt::decrypt($id))
          ->update([
            'stat' => 1,
            'keluar' => now(),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->route('pasien_ruang');
    }

    public function pasien_batal_pulang($id){
      $ruang  = DB::table('dt_pasien_ruang')
                  ->where('dt_pasien_ruang.id_pasien',Crypt::decrypt($id))
                  ->orderby('dt_pasien_ruang.id','desc')
                  ->first();

      DB::table('dt_pasien_ruang')
        ->where('id',$ruang->id)
        ->update([
          'stat' => 0,
          'keluar' => NULL,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_pasien')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'stat' => 0,
          'keluar' => NULL,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_pasien_layanan')
        ->where('id_pasien',Crypt::decrypt($id))
        ->update([
          'keluar' => NULL,
          'petugas_update' => Auth::user()->id,
        ]);

      return back();
    }

    public function pasien_batal($id){
        DB::table('dt_pasien_layanan')
            ->where('id_pasien',Crypt::decrypt($id))
            ->delete();

        DB::table('dt_pasien_ruang')
            ->where('id_pasien',Crypt::decrypt($id))
            ->delete();

        DB::table('dt_pasien')
            ->where('id',Crypt::decrypt($id))
            ->delete();

        return redirect()->route('pasien_ruang');
    }

    public function pasien_dpjp_baru(request $request){
        $pasien = DB::table('dt_pasien')
                    ->where('id',$request->id_pasien)
                    ->first();

        DB::table('dt_pasien')
          ->where('id',$request->id_pasien)
          ->update([
            'id_dpjp' => $request->id_dpjp,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien_ruang')
          ->where('id',$request->id_pasien_ruang)
          ->update([
              'id_dpjp' => $request->id_dpjp,
              'id_dpjp_real' => $request->id_dpjp,
              'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien_layanan')
          ->where('id_pasien',$request->id_pasien)
          ->update([
            'id_dpjp' => $request->id_dpjp,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_pasien_layanan')
          ->where('id_pasien_ruang',$request->id_pasien_ruang)
          ->whereNull('id_dpjp_real')
          ->update([
            'id_dpjp_real' => $request->id_dpjp,
            'petugas_update' => Auth::user()->id,
          ]);

        return back();
    }

    public function pasien_pindah(request $request){
        $ruang      = DB::table('dt_pasien_ruang')
                        ->where('id',$request->id_pasien_ruang)
                        ->first();

        DB::table('dt_pasien_ruang')
            ->where('id',$request->id_pasien_ruang)
            ->update([
                'stat' => 1,
                'keluar' => now(),
                'petugas_update' => Auth::user()->id,
            ]);

        $tujuan     = DB::table('dt_ruang')
                        ->where('id',$request->id_ruang)
                        ->first();

        if($tujuan->inap == 1){
            DB::table('dt_pasien_ruang')
                ->insert([
                    'id_pasien' => $request->id_pasien,
                    'id_ruang' => $tujuan->id,
                    'id_pasien_jenis' => $ruang->id_pasien_jenis,
                    'id_pasien_jenis_rawat' => 2,
                    'masuk' => now(),
                    'id_petugas' => Auth::user()->id,
                    'petugas_update' => Auth::user()->id,
                    'petugas_create' => Auth::user()->id,
                ]);

            DB::table('dt_pasien')
                ->where('id',$request->id_pasien)
                ->update([
                    'id_ruang_asal' => Auth::user()->id_ruang,
                    'id_ruang' => $tujuan->id,
                    'id_pasien_jenis' => $ruang->id_pasien_jenis,
                    'id_pasien_jenis_rawat' => 2,
                    'petugas_update' => Auth::user()->id,
                ]);
        } else {
            DB::table('dt_pasien_ruang')
                ->insert([
                    'id_pasien' => $request->id_pasien,
                    'id_ruang' => $tujuan->id,
                    'id_pasien_jenis' => $ruang->id_pasien_jenis,
                    'id_pasien_jenis_rawat' => 1,
                    'masuk' => now(),
                    'id_petugas' => Auth::user()->id,
                    'id_dpjp' => $ruang->id_dpjp,
                    'petugas_update' => Auth::user()->id,
                    'petugas_create' => Auth::user()->id,
                ]);

            DB::table('dt_pasien')
                ->where('id',$request->id_pasien)
                ->update([
                    'id_ruang_asal' => Auth::user()->id_ruang,
                    'id_ruang' => $tujuan->id,
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        return redirect()->route('pasien_ruang');
    }

    public function pasien_layanan(request $request){
        $perhitungan    = DB::table('dt_perhitungan')
                            ->where('id_jasa',$request->id_jasa)
                            ->where('id_jenis_pasien',$request->id_jenis)
                            ->first();

        if($request->id_jasa == 3){
            DB::table('dt_pasien_layanan')
                ->insert([
                    'waktu' => now(),
                    'id_petugas' => Auth::user()->id,
                    'id_pasien' => $request->id_pasien,
                    'id_jenis' => $request->id_jenis,
                    'id_pasien_ruang' => $request->id_pasien_ruang,
                    'id_pasien_layanan' => $request->id_pasien,
                    'id_ruang' => Auth::user()->id_ruang,
                    'id_ruang_sub' => Auth::user()->id_ruang,
                    'id_konsul' => $request->id_dpjp,
                    'id_perhitungan' => $perhitungan->id,
                    'id_jasa' => $request->id_jasa,
                    'tarif' => $request->tarif,
                    'petugas_update' => Auth::user()->id,
                    'petugas_create' => Auth::user()->id,
                ]);
        } else {
            if($request->id_jasa == 4){
                DB::table('dt_pasien_layanan')
                    ->insert([
                        'waktu' => now(),
                        'id_petugas' => Auth::user()->id,
                        'id_pasien' => $request->id_pasien,
                        'id_jenis' => $request->id_jenis,
                        'id_pasien_ruang' => $request->id_pasien_ruang,
                        'id_pasien_layanan' => $request->id_pasien,
                        'id_ruang' => Auth::user()->id_ruang,
                        'id_ruang_sub' => Auth::user()->id_ruang,
                        'id_pengganti' => $request->id_dpjp,
                        'id_perhitungan' => $perhitungan->id,
                        'id_jasa' => $request->id_jasa,
                        'tarif' => $request->tarif,
                        'petugas_update' => Auth::user()->id,
                        'petugas_create' => Auth::user()->id,
                    ]);
            } else {
                DB::table('dt_pasien_layanan')
                    ->insert([
                        'waktu' => now(),
                        'id_petugas' => Auth::user()->id,
                        'id_pasien' => $request->id_pasien,
                        'id_jenis' => $request->id_jenis,
                        'id_pasien_ruang' => $request->id_pasien_ruang,
                        'id_pasien_layanan' => $request->id_pasien,
                        'id_ruang' => Auth::user()->id_ruang,
                        'id_ruang_sub' => Auth::user()->id_ruang,
                        'id_perhitungan' => $perhitungan->id,
                        'id_jasa' => $request->id_jasa,
                        'tarif' => $request->tarif,
                        'petugas_update' => Auth::user()->id,
                        'petugas_create' => Auth::user()->id,
                    ]);
            }
        }

        return back();
    }

    public function pasien_layanan_multi(request $request){
      $dpjp = DB::table('dt_pasien_ruang')
                ->where('id',$request->id_pasien_ruang)
                ->selectRaw('dt_pasien_ruang.id_dpjp')
                ->first();

      foreach($request->id_jasa as $key => $id_jasa) {
        if($request->input('tarif')[$key] && $request->input('tarif')[$key] > 0){
          if($request->input('id_jasa')[$key] == 2){
            DB::table('dt_pasien_layanan')
              ->insert([
                  'waktu' => now(),
                  'id_petugas' => Auth::user()->id,
                  'id_pasien' => $request->id_pasien,
                  'id_pasien_ruang' => $request->id_pasien_ruang,
                  'id_pasien_layanan' => $request->id_pasien,
                  'id_ruang' => Auth::user()->id_ruang,
                  'id_ruang_sub' => Auth::user()->id_ruang,
                  'id_dpjp' => $dpjp->id_dpjp,
                  'id_dpjp_real' => $request->input('id_dpjp')[$key],
                  'id_jasa' => $request->input('id_jasa')[$key],
                  'id_pasien_jenis' => $request->id_pasien_jenis,
                  'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                  'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
              ]);
          }

          if($request->input('id_jasa')[$key] == 3){
            DB::table('dt_pasien_layanan')
              ->insert([
                  'waktu' => now(),
                  'id_petugas' => Auth::user()->id,
                  'id_pasien' => $request->id_pasien,
                  'id_pasien_ruang' => $request->id_pasien_ruang,
                  'id_pasien_layanan' => $request->id_pasien,
                  'id_ruang' => Auth::user()->id_ruang,
                  'id_ruang_sub' => Auth::user()->id_ruang,
                  'id_dpjp' => $dpjp->id_dpjp,
                  'id_dpjp_real' => $dpjp->id_dpjp,
                  'id_konsul' => $request->input('id_dpjp')[$key],
                  'id_jasa' => $request->input('id_jasa')[$key],
                  'id_pasien_jenis' => $request->id_pasien_jenis,
                  'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                  'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
              ]);
          }

          if($request->input('id_jasa')[$key] == 4){
            DB::table('dt_pasien_layanan')
              ->insert([
                  'waktu' => now(),
                  'id_petugas' => Auth::user()->id,
                  'id_pasien' => $request->id_pasien,
                  'id_pasien_ruang' => $request->id_pasien_ruang,
                  'id_pasien_layanan' => $request->id_pasien,
                  'id_ruang' => Auth::user()->id_ruang,
                  'id_ruang_sub' => Auth::user()->id_ruang,
                  'id_dpjp' => $dpjp->id_dpjp,
                  'id_dpjp_real' => $dpjp->id_dpjp,
                  'id_pengganti' => $request->input('id_dpjp')[$key],
                  'id_jasa' => $request->input('id_jasa')[$key],
                  'id_pasien_jenis' => $request->id_pasien_jenis,
                  'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                  'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
              ]);
          }

          if($request->input('id_jasa')[$key] <> 2 && $request->input('id_jasa')[$key] <> 3 && $request->input('id_jasa')[$key] <> 4){
            DB::table('dt_pasien_layanan')
              ->insert([
                  'waktu' => now(),
                  'id_petugas' => Auth::user()->id,
                  'id_pasien' => $request->id_pasien,
                  'id_pasien_ruang' => $request->id_pasien_ruang,
                  'id_pasien_layanan' => $request->id_pasien,
                  'id_ruang' => Auth::user()->id_ruang,
                  'id_ruang_sub' => Auth::user()->id_ruang,
                  'id_dpjp' => $dpjp->id_dpjp,
                  'id_dpjp_real' => $dpjp->id_dpjp,
                  'id_jasa' => $request->input('id_jasa')[$key],
                  'id_pasien_jenis' => $request->id_pasien_jenis,
                  'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                  'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
              ]);
            }
          }
      }

      return back();
    }

    public function pasien_layanan_multi_lain(request $request){
      $pasien   = DB::table('dt_pasien')
                    ->where('id',$request->id_pasien)
                    ->first();

      foreach($request->id_jasa as $key => $id_jasa) {
        if($request->input('tarif')[$key] && $request->input('tarif')[$key] > 0){
          if($request->input('id_jasa')[$key] == 2){
            DB::table('dt_pasien_layanan')
              ->insert([
                  'waktu' => now(),
                  'id_petugas' => Auth::user()->id,
                  'id_pasien' => $request->id_pasien,
                  'id_pasien_ruang' => $request->id_pasien_ruang,
                  'id_pasien_layanan' => $request->id_pasien,
                  'id_ruang' => $request->id_ruang,
                  'id_ruang_sub' => Auth::user()->id_ruang,
                  'id_dpjp' => $pasien->id_dpjp,
                  'id_dpjp_real' => $request->input('id_dpjp')[$key],
                  'id_jasa' => $request->input('id_jasa')[$key],
                  'id_pasien_jenis' => $request->id_pasien_jenis,
                  'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                  'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
              ]);
          }

        if($request->input('id_jasa')[$key] == 3){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien' => $request->id_pasien,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp_real,
                'id_konsul' => $request->input('id_dpjp')[$key],
                'id_jasa' => $request->input('id_jasa')[$key],
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if($request->input('id_jasa')[$key] == 4){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien' => $request->id_pasien,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp_real,
                'id_pengganti' => $request->input('id_dpjp')[$key],
                'id_jasa' => $request->input('id_jasa')[$key],
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if($request->input('id_jasa')[$key] <> 2 && $request->input('id_jasa')[$key] <> 3 && $request->input('id_jasa')[$key] <> 4){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien' => $request->id_pasien,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp_real,
                'id_jasa' => $request->input('id_jasa')[$key],
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
          }
        }
      }

      return back();
    }

    public function pasien_layanan_apotik(request $request){
      $pasien   = DB::table('dt_pasien_ruang')
                    ->where('id_pasien',$request->id_pasien)
                    ->selectRaw('dt_pasien_ruang.id_dpjp')
                    ->where('dt_pasien_ruang.stat',0)
                    ->first();

      if($pasien && $pasien->id_dpjp){
        $dpjp   = $pasien->id_dpjp;
      } else {
        $dpjp   = NULL;
      }

      foreach($request->id_jasa as $key => $id_jasa) {
        if($request->input('tarif')[$key] && $request->input('tarif')[$key] > 0){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien' => $request->id_pasien,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => 30,
                'id_dpjp' => $dpjp,
                'id_dpjp_real' => $dpjp,
                'id_jasa' => $request->input('id_jasa')[$key],
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->input('tarif')[$key]),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }
      }
      return back();
    }

    public function pasien_operasi(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien.id AS id_pasien,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     dt_pasien.tgl_data,
                                     dt_pasien.nama,                                   
                                     dt_pasien.no_mr,
                                     dt_pasien.stat,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) AS dpjp,
                                     dt_pasien_ruang.id AS id_pasien_ruang,
                                     dt_pasien.id_ruang,
                                     dt_ruang.ruang,
                                     dt_pasien_ruang.id_dpjp,
                                     dt_pasien_ruang.id_pasien_jenis,
                                     dt_pasien_ruang.id_pasien_jenis_rawat')
                        ->where('dt_pasien_ruang.stat',0)
                        ->where('dt_pasien_ruang.id_pasien_jenis_rawat',2)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','like','%'.$cari.'%')
                                         ->where('dt_pasien_ruang.stat',0)
                                         ->where('dt_pasien_ruang.id_pasien_jenis_rawat',2)
                                         ->orwhere('dt_pasien.no_mr',$cari)
                                         ->where('dt_pasien_ruang.stat',0)
                                         ->where('dt_pasien_ruang.id_pasien_jenis_rawat',2);
                        })

                        ->orderby('dt_pasien.nama')
                        ->get();

        if($request->id_pasien){
            $cek    = DB::table('dt_pasien')
                        ->where('id',Crypt::decrypt($request->id_pasien))
                        ->where('stat',0)
                        ->where('hapus',0)
                        ->count();

            if($cek > 0){
            $id_pasien  = Crypt::decrypt($request->id_pasien);

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,                                         
                                         dt_pasien.temp_lahir,
                                         dt_pasien.tgl_lahir,
                                         dt_pasien.id_ruang,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         dt_pasien_ruang.id_dpjp,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();            

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         (SELECT 
                                          CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                          IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users
                                          WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',5)
                            ->get();

            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',5)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->orderby('jasa')
                        ->get();
            } else {
                $id_pasien  = '';
                $pass       = '';
                $layanan    = '';
                $jasa       = '';
            }
        } else {
            $id_pasien  = '';
            $pass       = '';
            $layanan    = '';
            $jasa       = '';
        }   

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)
                    ->orderby('dt_ruang.ruang')
                    ->get();

        $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();        


        $anastesi = DB::table('users')
                    ->where('users.id_ruang',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_2',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $pendamping = DB::table('users')
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $operator   = DB::table('users')
                    ->where('users.id_tenaga_bagian',23)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_operasi',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang','operator','anastesi','pendamping'));
        } else {
            return view('pasien_operasi',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang','operator','anastesi','pendamping'));
        }
    }

    public function pasien_operasi_mobile(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }
        
        $id_pasien  = $request->id_pasien;

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,                                         
                                         dt_pasien.temp_lahir,
                                         dt_pasien.tgl_lahir,
                                         dt_pasien.id_ruang,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         dt_pasien_ruang.id_dpjp,
                                         (SELECT 
                                          CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                          IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users
                                          WHERE users.id = dt_pasien_ruang.id_dpjp) as dpjp,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_ruang.id_ruang) as ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();            

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         (SELECT 
                                          CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                          IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                          FROM users
                                          WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',5)
                            ->get();

            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',5)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->orderby('jasa')
                        ->get();            

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)
                    ->orderby('dt_ruang.ruang')
                    ->get();

        $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();        


        $anastesi = DB::table('users')
                    ->where('users.id_ruang',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_1',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->orwhere('users.id_ruang_2',72)
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $pendamping = DB::table('users')
                    ->where('users.id_tenaga_bagian',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $operator   = DB::table('users')
                    ->where('users.id_tenaga_bagian',23)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      return view('mobile.pasien_operasi_detil',compact('cari','pass','layanan','jasa','ruang','id_ruang','operator','anastesi','pendamping'));      
    }

    public function pasien_operasi_detil($id){
        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->where('dt_pasien.id',Crypt::decrypt($id))
                        ->where('dt_pasien_ruang.stat',0)
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     dt_pasien.no_mr,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,                                         
                                     dt_pasien.umur_thn,    
                                     dt_pasien.umur_bln,    
                                     dt_pasien.temp_lahir,
                                     dt_pasien.tgl_lahir,
                                     dt_pasien.id_ruang,
                                     dt_ruang.ruang,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%y"), " Th. ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%m"), " Bln ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%d"), " Hari") AS umur,
                                     dt_pasien.id_jenis,
                                     dt_pasien_ruang.id_dpjp,
                                     dt_pasien_ruang.id as id_pasien_ruang,
                                     dt_pasien_jenis.jenis as jenis_pasien')
                        ->first();            

        $layanan    = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,     
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',5)
                            ->get();

        $jasa   = DB::table('dt_perhitungan')     
                        ->leftjoin('dt_ruang_jasa','dt_perhitungan.id_jasa','=','dt_ruang_jasa.id_jasa')
                        ->selectRaw('dt_perhitungan.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_perhitungan.id_jenis_pasien,
                                     (SELECT dt_jasa.jasa FROM dt_jasa
                                     WHERE dt_jasa.id = dt_ruang_jasa.id_jasa) as jasa')
                        ->where('dt_ruang_jasa.id_ruang',5)
                        ->where('dt_perhitungan.id_jenis_pasien',$pasien->id_jenis)
                        ->where('dt_perhitungan.hapus',0)
                        ->get();            

        $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        return view('mobile.pasien_operasi_detil',compact('pasien','pasien','layanan','jasa','dpjp'));
    }

    public function cek_anastesi(request $request)
    {      
        if($request->ajax()) {
            $output='';
            $pdesa  = DB::table('dt_perhitungan')                     
                        ->leftjoin('dt_perhitungan_3','dt_perhitungan.id','=','dt_perhitungan_3.id_perhitungan')
                        ->selectRaw('dt_perhitungan_3.id')
                        ->where('dt_perhitungan_3.id_rekening',21)
                        ->where('dt_perhitungan.id_jasa',$request->id_jasa)
                        ->where('dt_perhitungan.id_pasien_jenis',$request->id_pasien_jenis)
                        ->where('dt_perhitungan.id_pasien_jenis_rawat',$request->id_pasien_jenis_rawat)
                        ->count();

            $output = $pdesa;

            return response($output);
        }      
    }

    public function cek_pendamping(request $request)
    {      
        if($request->ajax()) {
            $output='';
            $pdesa  = DB::table('dt_perhitungan')                     
                        ->leftjoin('dt_perhitungan_3','dt_perhitungan.id','=','dt_perhitungan_3.id_perhitungan')
                        ->selectRaw('dt_perhitungan_3.id')
                        ->where('dt_perhitungan_3.id_rekening',29)
                        ->where('dt_perhitungan.id_jasa',$request->id_jasa)
                        ->where('dt_perhitungan.id_pasien_jenis',$request->id_pasien_jenis)
                        ->where('dt_perhitungan.id_pasien_jenis_rawat',$request->id_pasien_jenis_rawat)
                        ->count();

            $output = $pdesa;
            
            return response($output);
        }      
    }

    public function cek_tanggung(request $request)
    {      
        if($request->ajax()) {
            $output='';
            $pdesa  = DB::table('dt_perhitungan')                     
                        ->leftjoin('dt_perhitungan_3','dt_perhitungan.id','=','dt_perhitungan_3.id_perhitungan')
                        ->selectRaw('dt_perhitungan_3.id')
                        ->where('dt_perhitungan_3.id_rekening',30)
                        ->where('dt_perhitungan.id_jasa',$request->id_jasa)
                        ->where('dt_perhitungan.id_pasien_jenis',$request->id_pasien_jenis)
                        ->where('dt_perhitungan.id_pasien_jenis_rawat',$request->id_pasien_jenis_rawat)
                        ->count();

            $output = $pdesa;

            return response($output);
        }      
    }

    public function pasien_apotik(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien.id AS id_pasien,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien.tgl_data,
                                     dt_pasien.nama,
                                     dt_pasien.no_mr,
                                     dt_pasien_jenis.jenis as jenis_pasien,                                     
                                     dt_ruang.ruang')
                        ->where('dt_pasien_ruang.stat',0)                        

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','like','%'.$cari.'%')
                                         ->where('dt_pasien_ruang.stat',0)
                                         ->orwhere('dt_pasien.no_mr',$cari)
                                         ->where('dt_pasien_ruang.stat',0);
                        })

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->orderby('dt_pasien.nama')
                        ->get();

        if($request->id_pasien){
            $cek    = DB::table('dt_pasien')
                        ->where('id',Crypt::decrypt($request->id_pasien))
                        ->where('stat',0)
                        ->where('hapus',0)
                        ->count();

            if($cek > 0){
            $id_pasien  = Crypt::decrypt($request->id_pasien);

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         dt_pasien.id_ruang,
                                         (SELECT 
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                         FROM users
                                         WHERE users.id = dt_pasien_ruang.id_dpjp) as dpjp,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_ruang.id_ruang) as ruang,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',30)
                            ->get();
            } else {
                $id_pasien  = '';
                $pass       = '';
                $layanan    = '';
            }
        } else {
            $id_pasien  = '';
            $pass       = '';
            $layanan    = '';
        }

        $jasa   = DB::table('dt_jasa')
                    ->where('dt_jasa.id',43)
                    ->where('dt_jasa.hapus',0)

                    ->orwhere('dt_jasa.id',44)
                    ->where('dt_jasa.hapus',0)

                    ->get();

        $ruang  = DB::table('dt_ruang')
                    ->where('jalan',1)
                    ->where('hapus',0)
                    ->orwhere('inap',1)
                    ->where('hapus',0)
                    ->orderby('ruang')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_apotik',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang'));
        } else {
            return view('pasien_apotik',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang'));
        }
    }

    public function pasien_apotik_mobile(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        
        $id_pasien  = $request->id_pasien;

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         dt_pasien.id_ruang,
                                         (SELECT 
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                         FROM users
                                         WHERE users.id = dt_pasien_ruang.id_dpjp) as dpjp,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_ruang.id_ruang) as ruang,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',30)
                            ->get();            

        $jasa   = DB::table('dt_jasa')
                    ->where('dt_jasa.id',43)
                    ->where('dt_jasa.hapus',0)

                    ->orwhere('dt_jasa.id',44)
                    ->where('dt_jasa.hapus',0)

                    ->get();

        $ruang  = DB::table('dt_ruang')
                    ->where('jalan',1)
                    ->where('hapus',0)
                    ->orwhere('inap',1)
                    ->where('hapus',0)
                    ->orderby('ruang')
                    ->get();

        
        return view('mobile.pasien_apotik_detil',compact('id_pasien','cari','pass','layanan','jasa','ruang','id_ruang'));        
    }

    public function pasien_apotik_detil($id){
        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->where('dt_pasien.id',Crypt::decrypt($id))
                        ->where('dt_pasien_ruang.stat',0)
                        ->selectRaw('dt_pasien.id,
                                     dt_pasien.no_mr,
                                     dt_pasien.nama,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,                                     
                                     dt_pasien_ruang.id_dpjp,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     dt_ruang.ruang,
                                     dt_pasien_ruang.id_jenis,
                                     dt_pasien_ruang.id_ruang,
                                     dt_pasien_ruang.id as id_pasien_ruang,
                                     dt_pasien_jenis.jenis as jenis_pasien')
                        ->first();        

        $layanan    = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     dt_pasien_layanan.id_pasien_ruang,
                                     dt_pasien_layanan.id_ruang_sub,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as waktu,
                                     dt_jasa.jasa,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                     dt_pasien_layanan.tarif')
                        ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                        ->where('dt_pasien_layanan.id_ruang_sub',30)
                        ->get();

        $jasa   = DB::table('dt_jasa')
                    ->where('dt_jasa.id',43)
                    ->where('dt_jasa.hapus',0)

                    ->orwhere('dt_jasa.id',44)
                    ->where('dt_jasa.hapus',0)

                    ->get();

        return view('mobile.pasien_apotik_detil',compact('pasien','layanan','jasa'));
    }

    public function pasien_apotik_non(request $request){
        DB::table('dt_pasien')
            ->insert([
                'tgl_data' => date("Y-m-d"),
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'umur_thn' => $request->umur_thn,
                'umur_bln' => $request->umur_bln,
                'id_kelamin' => $request->id_kelamin,
                'id_petugas' => Auth::user()->id,
                'id_ruang' => 30,
                'masuk' => now(),
                'keluar' => now(),
                'keterangan' => $request->keterangan,
                'non_pasien' => 1,
                'stat' => 1,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'id_ruang_asal' => 30,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        $pasien     = DB::table('dt_pasien')
                        ->where('dt_pasien.id_ruang',30)
                        ->where('dt_pasien.non_pasien',1)
                        ->where('dt_pasien.stat',1)
                        ->orderby('dt_pasien.id','desc')
                        ->first();

        DB::table('dt_pasien_ruang')
            ->insert([
                'id_pasien' => $pasien->id,
                'id_ruang' => 30,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'masuk' => now(),
                'keluar' => now(),
                'id_petugas' => Auth::user()->id,
                'id_ruang_sub' => 30,
                'stat' => 1,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        $pasien_ruang   = DB::table('dt_pasien_ruang')
                            ->where('dt_pasien_ruang.id_pasien',$pasien->id)
                            ->where('dt_pasien_ruang.id_ruang',30)
                            ->where('dt_pasien_ruang.id_pasien_jenis',1)
                            ->where('dt_pasien_ruang.id_pasien_jenis_rawat',1)
                            ->where('dt_pasien_ruang.stat',1)
                            ->orderby('dt_pasien_ruang.id','desc')
                            ->first();

        DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $pasien_ruang->id,
                'id_pasien_layanan' => $pasien->id,
                'id_pasien' => $pasien->id,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'id_ruang' => 30,
                'id_ruang_sub' => 30,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return back();
    }

    public function pasien_gizi(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        $pasien     = DB::table('dt_pasien_ruang')                        
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_ruang','dt_pasien.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien.id AS id_pasien,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     dt_pasien.tgl_data,
                                     dt_pasien.nama,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien.no_mr,
                                     dt_pasien_jenis.jenis as jenis_pasien,                                     
                                     dt_ruang.ruang')
                        ->where('dt_pasien_ruang.stat',0)
                        ->where('dt_pasien_ruang.id_pasien_jenis_rawat','>',1)                       

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','like','%'.$cari.'%')
                                         ->orwhere('dt_pasien.no_mr',$cari);
                        })

                        ->orderby('dt_pasien.nama')
                        ->get();

        if($request->id_pasien){
            $cek    = DB::table('dt_pasien')
                        ->where('id',Crypt::decrypt($request->id_pasien))
                        ->where('stat',0)
                        ->where('hapus',0)
                        ->count();

            if($cek > 0){
            $id_pasien  = Crypt::decrypt($request->id_pasien);

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien.id_ruang,
                                         dt_pasien_ruang.id_dpjp,
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_ruang.ruang,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%W, %d %M %Y - %H:%i:%s") as waktu,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',31)
                            ->get();
            } else {
                $id_pasien  = '';
                $pass       = '';
                $layanan    = '';
            }
        } else {
            $id_pasien  = '';
            $pass       = '';
            $layanan    = '';
        }

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)
                    ->orderby('ruang')
                    ->get();

        $jasa   = DB::table('dt_jasa')
                    ->where('dt_jasa.id',6)
                    ->where('dt_jasa.hapus',0)
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_gizi',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang'));
        } else {
            return view('pasien_gizi',compact('pasien','cari','pass','layanan','jasa','ruang','id_ruang'));
        }
    }

    public function pasien_gizi_mobile(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }
        
            $id_pasien  = $request->id_pasien;

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien.id_ruang,
                                         dt_pasien_ruang.id_dpjp,
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_ruang.ruang,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         (SELECT dt_ruang.ruang
                                          FROM dt_ruang
                                          WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%W, %d %M %Y - %H:%i:%s") as waktu,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pass->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',31)
                            ->get();            

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)
                    ->orderby('ruang')
                    ->get();

        $jasa   = DB::table('dt_jasa')
                    ->where('dt_jasa.id',6)
                    ->where('dt_jasa.hapus',0)
                    ->get();
        
        return view('mobile.pasien_gizi_detil',compact('cari','pass','layanan','jasa','ruang','id_ruang'));        
    }

    public function pasien_gizi_detil($id){
        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->where('dt_pasien.id',Crypt::decrypt($id))
                        ->where('dt_pasien_ruang.stat',0)
                        ->selectRaw('dt_pasien.id,
                                     dt_pasien.no_mr,
                                     dt_pasien.nama,                                     
                                     dt_pasien.id_jenis,
                                     dt_pasien.id_ruang,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien_ruang.id_dpjp,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     dt_ruang.ruang,
                                     dt_pasien_ruang.id as id_pasien_ruang,
                                     dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

        $layanan    = DB::table('dt_pasien_layanan')
                            ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.id_pasien_ruang,
                                         dt_pasien_layanan.id_ruang_sub,
                                         DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                         dt_jasa.jasa,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                            ->where('dt_pasien_layanan.id_ruang_sub',31)
                            ->get();

        return view('mobile.pasien_gizi_detil',compact('pasien','layanan'));
    }

    public function pasien_gizi_layanan(request $request){        
        $ruang    = DB::table('dt_pasien_ruang')
                      ->where('id_pasien',$request->id_pasien)
                      ->where('stat',0)
                      ->first();

        if($ruang && $ruang->id_dpjp){
          $dpjp   = $ruang->id_dpjp;
        } else {
          $dpjp   = NULL;
        }

        DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $ruang->id,
                'id_pasien_layanan' => $request->id_pasien,
                'id_pasien' => $request->id_pasien,
                'id_ruang' => $ruang->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $dpjp,
                'id_dpjp_real' => $dpjp,
                'id_jasa' => 6,
                'id_pasien_jenis' => $ruang->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $ruang->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return back();
    }

    public function pasien_upp(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_ruang','dt_pasien.id_ruang','=','dt_ruang.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                     dt_pasien.no_mr,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     UPPER(dt_ruang.ruang) as ruang,
                                     UPPER(dt_pasien_jenis.jenis) as jenis_pasien,
                                     dt_pasien.tagihan')
                        ->where('dt_pasien.stat',1)
                        ->where('dt_pasien.upp',0)
                        ->where('dt_pasien.hapus',0)
                        ->where('dt_pasien.id_pasien_jenis',1)

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%');
                        })

                        ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_upp',compact('pasien','cari'));
        } else {
            return view('pasien_upp',compact('pasien','cari'));
        }
    }

    public function pasien_upp_data(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_ruang','dt_pasien.id_ruang','=','dt_ruang.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien.id_petugas_upp','=','users.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                     dt_pasien.no_mr,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     DATE_FORMAT(dt_pasien.waktu_upp, "%d/%m/%Y - %H:%i") as waktu_upp,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                     UPPER(dt_ruang.ruang) as ruang,
                                     UPPER(dt_pasien_jenis.jenis) as jenis_pasien,
                                     (SELECT SUM(dt_pasien_layanan.tarif)
                                      FROM dt_pasien_layanan
                                      WHERE dt_pasien_layanan.id_pasien = dt_pasien.id) as tagihan')
                        ->where('dt_pasien.upp',1)
                        ->where('dt_pasien.hapus',0)
                        ->where('dt_pasien.id_pasien_jenis',1)
                        ->whereDate('dt_pasien.waktu_upp','>=',$awal)
                        ->whereDate('dt_pasien.waktu_upp','<=',$akhir)
                        ->get();

        $total      = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_layanan','dt_pasien.id','=','dt_pasien_layanan.id_pasien')
                        ->selectRaw('SUM(dt_pasien_layanan.tarif) as tagihan')
                        ->where('dt_pasien.upp',1)
                        ->where('dt_pasien.hapus',0)
                        ->where('dt_pasien.id_pasien_jenis',1)
                        ->whereDate('dt_pasien.waktu_upp','>=',$awal)
                        ->whereDate('dt_pasien.waktu_upp','<=',$akhir)
                        ->first();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_upp_data',compact('pasien','awal','akhir','total'));
        } else {
            return view('pasien_upp_data',compact('pasien','awal','akhir','total'));
        }
    }

    public function pasien_upp_verifikasi($id){
        DB::table('dt_pasien')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'upp' => 1,
                'waktu_upp' => now(),
                'id_petugas_upp' => Auth::user()->id,
                'petugas_update' => Auth::user()->id,
            ]);

        return back();
    }

    public function pasien_upp_revisi($id){
        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data), LPAD(dt_pasien.register, 6, 0), MONTH(dt_pasien.tgl_data)) AS register,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien.no_mr,
                                     dt_pasien.id_pasien_jenis,
                                     UPPER(dt_pasien_jenis.jenis) AS jenis_pasien,
                                     DATE_FORMAT(dt_pasien.masuk, "%W, %d %M %Y") as masuk,
                                     DATE_FORMAT(dt_pasien.keluar, "%W, %d %M %Y") as keluar,

                                     (SELECT SUM(dt_pasien_layanan.tarif)
                                      FROM dt_pasien_layanan
                                      WHERE dt_pasien_layanan.id_pasien = dt_pasien.id) as tagihan')
                        ->where('dt_pasien.id',Crypt::decrypt($id))
                        ->first();

        $layanan    = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang_sub','=','dt_ruang.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     dt_pasien_layanan.id_pasien_layanan,
                                     dt_jasa.jasa,
                                     dt_pasien_layanan.tarif,
                                     dt_ruang.ruang,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%W, %d %M %Y - %H:%i:%s") as waktu,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak')
                        ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                        ->orderby('dt_pasien_layanan.id')
                        ->get();  

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_upp_revisi',compact('pasien','layanan'));
        } else {
            return view('pasien_upp_revisi',compact('pasien','layanan'));
        }        
    }

    public function pasien_upp_data_rincian($id){
        $pasien     = DB::table('dt_pasien')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien.id,
                                     CONCAT(YEAR(dt_pasien.tgl_data), LPAD(dt_pasien.register, 6, 0), MONTH(dt_pasien.tgl_data)) AS register,
                                     dt_pasien.nama,
                                     dt_pasien.alamat,
                                     CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%y"), " Th. ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%m"), " Bln.") AS umur,
                                     dt_pasien.no_mr,
                                     dt_pasien.id_jenis,
                                     UPPER(dt_pasien_jenis.jenis) AS jenis_pasien,
                                     DATE_FORMAT(dt_pasien.masuk, "%W, %d %M %Y") as masuk,
                                     DATE_FORMAT(dt_pasien.keluar, "%W, %d %M %Y") as keluar,
                                     (SELECT SUM(dt_pasien_layanan.tarif)
                                      FROM dt_pasien_layanan
                                      WHERE dt_pasien_layanan.id_pasien = dt_pasien.id) as tagihan')
                        ->where('dt_pasien.id',Crypt::decrypt($id))
                        ->first();

        $layanan    = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang_sub','=','dt_ruang.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     dt_pasien_layanan.id_pasien_layanan,
                                     dt_jasa.jasa,
                                     dt_pasien_layanan.tarif,
                                     dt_ruang.ruang,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%W, %d %M %Y - %H:%i:%s") as waktu')
                        ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                        ->orderby('dt_pasien_layanan.id')
                        ->get();  

        return view('pasien_upp_data_rincian',compact('pasien','layanan'));
    }

    public function pasien_apotik_transaksi(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        if($request->jns){
            $jns   = $request->jns;
        } else {
            $jns   = '';
        }

        if($request->rwt){
            $rwt   = $request->rwt;
        } else {
            $rwt   = '';
        }

        $transaksi  = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak,
                                     dt_jasa.jasa,
                                     dt_pasien.nama,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     dt_ruang.ruang,
                                     dt_pasien_layanan.tarif,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                     dt_pasien_layanan.apoteker,
                                     dt_pasien_layanan.ass_apoteker,
                                     dt_pasien_layanan.admin_farmasi')
                        ->where('dt_pasien_layanan.id_ruang_sub',30)
                        ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                        ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)

                        ->when($jns, function ($query) use ($jns) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis',$jns);
                        })

                        ->when($rwt, function ($query) use ($rwt) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis_rawat',$rwt);
                        })

                        ->get();

        $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

        $rawat  = DB::table('dt_pasien_jenis_rawat')->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.transaksi_apotik',compact('transaksi','awal','akhir','jenis','rawat','jns','rwt'));
        } else {
            return view('transaksi_apotik',compact('transaksi','awal','akhir','jenis','rawat','jns','rwt'));
        }        
    }

    public function pasien_jenasah_transaksi(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }        

        $transaksi  = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak,
                                     dt_jasa.jasa,
                                     dt_pasien.nama,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     dt_ruang.ruang,
                                     dt_pasien_layanan.tarif,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                     dt_pasien_layanan.pemulasaran')
                        ->where('dt_pasien_layanan.id_ruang_sub',52)
                        ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                        ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)

                        ->get();        

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.transaksi_jenasah',compact('transaksi','awal','akhir'));
        } else {
            return view('transaksi_jenasah',compact('transaksi','awal','akhir'));
        }        
    }

    public function pasien_layanan_operasi(request $request){        
        if($request->id_anastesi){
            $anastesi   = $request->id_anastesi;
        } else {
            $anastesi   = NULL;
        }

        if($request->id_pendamping){
            $pendamping   = $request->id_pendamping;
        } else {
            $pendamping   = NULL;
        }

        $pasien = DB::table('dt_pasien')
                    ->where('id',$request->id_pasien)
                    ->selectRaw('dt_pasien.id_dpjp')
                    ->first();

        DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien_layanan' => $request->id_pasien,
                'id_pasien' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_operator, 
                'id_operator' => $request->id_operator,
                'id_anastesi' => $anastesi,
                'id_pendamping' => $pendamping,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return back();
    }

    public function pasien_laborat(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        $pasien     = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                        ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien.id AS id_pasien,
                                     CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                     CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%y"), " Th. ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%m"), " Bln ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%d"), " Hari") AS umur,
                                     dt_pasien.tgl_data,
                                     dt_pasien.nama,                                   
                                     dt_pasien.no_mr,
                                     dt_pasien.stat,
                                     dt_pasien.umur_thn,
                                     dt_pasien.umur_bln,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) AS dpjp,
                                     dt_pasien_ruang.id AS id_pasien_ruang,
                                     dt_pasien.id_ruang,
                                     dt_ruang.ruang,
                                     dt_pasien_ruang.id_dpjp,
                                     dt_pasien_ruang.id_pasien_jenis,
                                     dt_pasien_ruang.id_pasien_jenis_rawat')
                        ->where('dt_pasien_ruang.stat',0)                        

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien.nama','like','%'.$cari.'%')
                                         ->where('dt_pasien_ruang.stat',0)
                                         ->orwhere('dt_pasien.no_mr',$cari)
                                         ->where('dt_pasien_ruang.stat',0);
                        })

                        ->orderby('dt_pasien.nama')
                        ->get();

        if($request->id_pasien){
            $cek    = DB::table('dt_pasien')
                        ->where('id',Crypt::decrypt($request->id_pasien))
                        ->where('stat',0)
                        ->where('hapus',0)
                        ->count();

            if($cek > 0){
            $id_pasien  = Crypt::decrypt($request->id_pasien);

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,                                         
                                         dt_pasien.temp_lahir,
                                         dt_pasien.tgl_lahir,
                                         dt_pasien.id_ruang,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_ruang.ruang,
                                         dt_pasien_ruang.id_dpjp,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_laborat) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 72){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 52){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 46){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_radiologi) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 47){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_rr) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }            

            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->get();
            } else {
                $id_pasien  = '';
                $pass       = '';
                $ruang      = '';
                $layanan    = '';
                $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->get();
            }
        } else {
            $id_pasien  = '';
            $pass       = '';
            $ruang      = '';
            $layanan    = '';
            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->get();
        }

        $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();

        $dpjp   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->where('users.id_ruang',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)                      
                      ->where('users.hapus',0)
                      ->orwhere('users.id_ruang_1',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->orwhere('users.id_ruang_2',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();

        if(count($dpjp) == 0){
          $dpjp   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();
        }

        $agent = new Agent();

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)

                    ->orwhere('dt_ruang.jalan',1)
                    ->where('dt_ruang.hapus',0)

                    ->orderby('dt_ruang.ruang')
                    ->get();
        
        if ($agent->isMobile()) {
            return view('mobile.pasien_laborat',compact('pasien','cari','pass','layanan','dpjp','jasa','ruang','id_ruang'));
        } else {
            return view('pasien_laborat',compact('pasien','cari','pass','layanan','dpjp','jasa','ruang','id_ruang'));
        }        
    }

    public function pasien_laborat_mobile(request $request){
        if($request->cari){
            $cari   = $request->cari;
        } else {
            $cari   = '';
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }
        
            $id_pasien  = $request->id_pasien;

            $pass       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->where('dt_pasien.id',$id_pasien)
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,                                         
                                         dt_pasien.temp_lahir,
                                         dt_pasien.tgl_lahir,
                                         dt_pasien.id_ruang,
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                         
                                         dt_pasien_ruang.id_pasien_jenis,
                                         dt_pasien_ruang.id_pasien_jenis_rawat,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_ruang.ruang,
                                         dt_pasien_ruang.id_dpjp,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_laborat) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 72){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 52){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 46){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_radiologi) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 47){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_rr) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pass->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }            

            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->get();            

        $c_ruang  = DB::table('dt_ruang')->where('id',Auth::user()->id_ruang)->first();

        $dpjp   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->where('users.id_ruang',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)                      
                      ->where('users.hapus',0)
                      ->orwhere('users.id_ruang_1',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->orwhere('users.id_ruang_2',Auth::user()->id_ruang)
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();

        if(count($dpjp) == 0){
          $dpjp   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->where('users_tenaga_bagian.medis',1)
                      ->where('users.hapus',0)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();
        }

        $ruang  = DB::table('dt_ruang')
                    ->where('dt_ruang.inap',1)
                    ->where('dt_ruang.hapus',0)

                    ->orwhere('dt_ruang.jalan',1)
                    ->where('dt_ruang.hapus',0)

                    ->orderby('dt_ruang.ruang')
                    ->get();
        
        return view('mobile.pasien_laborat_detil',compact('cari','pass','layanan','dpjp','jasa','ruang','id_ruang'));        
    }

    public function pasien_laborat_detil($id){
        $pasien       = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id')
                            ->where('dt_pasien.id',Crypt::decrypt($id))
                            ->where('dt_pasien_ruang.stat',0)
                            ->selectRaw('dt_pasien.id,
                                         CONCAT(DATE_FORMAT(dt_pasien.tgl_data, "%Y"), LPAD(dt_pasien.register, 6, 0), DATE_FORMAT(dt_pasien.tgl_data, "%m")) AS register,
                                         dt_pasien.no_mr,
                                         dt_pasien.nama,
                                         dt_pasien.alamat,      
                                         dt_pasien.umur_thn,
                                         dt_pasien.umur_bln,                                   
                                         dt_pasien.temp_lahir,
                                         dt_pasien.tgl_lahir,
                                         dt_pasien.id_ruang,
                                         CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%y"), " Th. ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%m"), " Bln ", DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(dt_pasien.tgl_lahir)), "%d"), " Hari") AS umur,
                                         dt_pasien.id_jenis,
                                         dt_pasien_ruang.id_dpjp,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_ruang.ruang,
                                         dt_pasien_ruang.id as id_pasien_ruang,
                                         dt_pasien_jenis.jenis as jenis_pasien')
                            ->first();

            if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_laborat) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 46){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d %b %Y - %H:%i:%s") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_radiologi) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }

            if(Auth::user()->id_ruang == 47){
                $layanan    = DB::table('dt_pasien_layanan')
                                ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                                ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_ruang_sub,
                                             DATE_FORMAT(dt_pasien_layanan.waktu, "%d %b %Y - %H:%i:%s") as waktu,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_rr) as dpjp,
                                             (SELECT 
                                              CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                              IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                              FROM users
                                              WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung')
                                ->where('dt_pasien_layanan.id_pasien',$pasien->id)
                                ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                                ->get();
            }            

            $jasa   = DB::table('dt_ruang_jasa')     
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_ruang_jasa.hapus',0)
                        ->get();

            $dpjp   = User::leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        return view('mobile.pasien_laborat_detil',compact('jasa','pasien','layanan','dpjp'));
    }


    public function pasien_laborat_transaksi(request $request){
        if($request->dari){
            $dari   = $request->dari;
        } else {
            $dari   = date("Y-m-d");
        }

        if($request->sampai){
            $sampai   = $request->sampai;
        } else {
            $sampai   = date("Y-m-d");
        }

        if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
          $layanan    = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                          ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                          ->selectRaw('dt_pasien_layanan.id,
                                      dt_pasien.nama,
                                      dt_ruang.ruang,
                                      dt_jasa.jasa,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_laborat) as dpjp,
                                      dt_pasien_layanan.jasa_laborat as jasa_medis,
                                      dt_pasien_layanan.jp_perawat,
                                      dt_pasien_layanan.administrasi,
                                      (SELECT dt_pasien_jenis.jenis
                                       FROM dt_pasien_jenis
                                       WHERE dt_pasien_jenis.id = dt_pasien_layanan.id_pasien_jenis) as jenis,
                                      DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                      dt_pasien_layanan.tarif')
                          ->whereDate('dt_pasien_layanan.waktu','>=',$dari)
                          ->whereDate('dt_pasien_layanan.waktu','<=',$sampai)
                          ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                          ->get();
        }

        if(Auth::user()->id_ruang == 46){
          $layanan    = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                          ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                          ->selectRaw('dt_pasien_layanan.id,
                                      dt_pasien.nama,
                                      dt_ruang.ruang,
                                      dt_jasa.jasa,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_radiologi) as dpjp,
                                      dt_pasien_layanan.jasa_radiologi as jasa_medis,
                                      dt_pasien_layanan.jp_perawat,
                                      dt_pasien_layanan.administrasi,
                                      (SELECT dt_pasien_jenis.jenis
                                       FROM dt_pasien_jenis
                                       WHERE dt_pasien_jenis.id = dt_pasien_layanan.id_pasien_jenis) as jenis,
                                      DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                      dt_pasien_layanan.tarif')
                          ->whereDate('dt_pasien_layanan.waktu','>=',$dari)
                          ->whereDate('dt_pasien_layanan.waktu','<=',$sampai)
                          ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                          ->get();
        }

        if(Auth::user()->id_ruang == 47){
          $layanan    = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                          ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                          ->selectRaw('dt_pasien_layanan.id,
                                      dt_pasien.nama,
                                      dt_ruang.ruang,
                                      dt_jasa.jasa,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_rr) as dpjp,
                                      dt_pasien_layanan.jasa_rr as jasa_medis,
                                      dt_pasien_layanan.jp_perawat,
                                      dt_pasien_layanan.administrasi,
                                      (SELECT dt_pasien_jenis.jenis
                                       FROM dt_pasien_jenis
                                       WHERE dt_pasien_jenis.id = dt_pasien_layanan.id_pasien_jenis) as jenis,
                                      DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                      dt_pasien_layanan.tarif')
                          ->whereDate('dt_pasien_layanan.waktu','>=',$dari)
                          ->whereDate('dt_pasien_layanan.waktu','<=',$sampai)
                          ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                          ->get();
        }

        if(Auth::user()->id_ruang == 52){
          $layanan    = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                          ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                          ->selectRaw('dt_pasien_layanan.id,
                                      dt_pasien.nama,
                                      dt_ruang.ruang,
                                      dt_jasa.jasa,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_rr) as dpjp,
                                      DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                      dt_pasien_layanan.tarif')
                          ->whereDate('dt_pasien_layanan.waktu','>=',$dari)
                          ->whereDate('dt_pasien_layanan.waktu','<=',$sampai)
                          ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                          ->get();
        }

        if(Auth::user()->id_ruang == 72){
          $layanan    = DB::table('dt_pasien_layanan')
                          ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                          ->leftjoin('dt_pasien_ruang','dt_pasien_layanan.id_pasien_ruang','=','dt_pasien_ruang.id')
                          ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                          ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                          ->selectRaw('dt_pasien_layanan.id,
                                      dt_pasien.nama,
                                      dt_ruang.ruang,
                                      dt_jasa.jasa,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                      (SELECT 
                                       CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                       IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                       FROM users
                                       WHERE users.id = dt_pasien_layanan.id_anastesi) as dpjp,
                                      dt_pasien_layanan.jasa_anastesi as jasa_medis,
                                      dt_pasien_layanan.jp_perawat,
                                      dt_pasien_layanan.administrasi,
                                      (SELECT dt_pasien_jenis.jenis
                                       FROM dt_pasien_jenis
                                       WHERE dt_pasien_jenis.id = dt_pasien_layanan.id_pasien_jenis) as jenis,
                                      DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                      dt_pasien_layanan.tarif')
                          ->whereDate('dt_pasien_layanan.waktu','>=',$dari)
                          ->whereDate('dt_pasien_layanan.waktu','<=',$sampai)
                          ->where('dt_pasien_layanan.id_ruang_sub',Auth::user()->id_ruang)
                          ->get();
        }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.transaksi_laborat',compact('dari','sampai','layanan'));
        } else {
            return view('transaksi_laborat',compact('dari','sampai','layanan'));
        }        
    }

    public function pasien_layanan_laborat(request $request){        
        if($request->id_tanggung){
            $tanggung   = $request->id_tanggung;
        } else {
            $tanggung   = NULL;
        }

        $ruang          = DB::table('dt_pasien_ruang')
                            ->where('id',$request->id_pasien_ruang)
                            ->first();

        $pasien         = DB::table('dt_pasien')
                            ->where('dt_pasien.id',$request->id_pasien)
                            ->selectRaw('dt_pasien.id_dpjp')
                            ->first();

        if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien' => $request->id_pasien,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_laborat' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 72){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien' => $request->id_pasien,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 52){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien' => $request->id_pasien,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $pasien->id_dpjp,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 46){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien' => $request->id_pasien,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_radiologi' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 47){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $request->id_pasien_ruang,
                'id_pasien' => $request->id_pasien,
                'id_pasien_layanan' => $request->id_pasien,
                'id_ruang' => $request->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $pasien->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_rr' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }        

        return back();
    }

    public function pasien_luar_laborat(request $request){        
        if($request->id_tanggung){
            $tanggung   = $request->id_tanggung;
        } else {
            $tanggung   = NULL;
        }

        DB::table('dt_pasien')
            ->insert([
                'tgl_data' => date("Y-m-d"),
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'umur_thn' => $request->umur_thn,
                'umur_bln' => $request->umur_bln,
                'id_kelamin' => $request->id_kelamin,
                'id_petugas' => Auth::user()->id,
                'id_ruang' => Auth::user()->id_ruang,
                'masuk' => now(),
                'keluar' => now(),
                'keterangan' => $request->keterangan,
                'non_pasien' => 1,
                'stat' => 1,
                'id_dpjp' => $request->id_dpjp,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'id_ruang_asal' => Auth::user()->id_ruang,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        $pasien     = DB::table('dt_pasien')
                        ->where('dt_pasien.id_ruang',Auth::user()->id_ruang)
                        ->where('dt_pasien.non_pasien',1)
                        ->where('dt_pasien.stat',1)
                        ->orderby('dt_pasien.id','desc')
                        ->first();

        DB::table('dt_pasien_ruang')
            ->insert([
                'id_pasien' => $pasien->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'masuk' => now(),
                'keluar' => now(),
                'id_petugas' => Auth::user()->id,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'stat' => 1,
                'id_dpjp' => $request->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        $pasien_ruang   = DB::table('dt_pasien_ruang')
                            ->where('dt_pasien_ruang.id_pasien',$pasien->id)
                            ->where('dt_pasien_ruang.id_ruang',Auth::user()->id_ruang)
                            ->where('dt_pasien_ruang.id_pasien_jenis',1)
                            ->where('dt_pasien_ruang.id_pasien_jenis_rawat',1)
                            ->where('dt_pasien_ruang.stat',1)
                            ->orderby('dt_pasien_ruang.id','desc')
                            ->first();

        if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 62){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'keluar' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $pasien_ruang->id,
                'id_pasien_layanan' => $pasien->id,
                'id_pasien' => $pasien->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $request->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_laborat' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 46){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'keluar' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $pasien_ruang->id,
                'id_pasien_layanan' => $pasien->id,
                'id_pasien' => $pasien->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $request->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_radiologi' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }

        if(Auth::user()->id_ruang == 47){
          DB::table('dt_pasien_layanan')
            ->insert([
                'waktu' => now(),
                'keluar' => now(),
                'id_petugas' => Auth::user()->id,
                'id_pasien_ruang' => $pasien_ruang->id,
                'id_pasien_layanan' => $pasien->id,
                'id_pasien' => $pasien->id,
                'id_ruang' => Auth::user()->id_ruang,
                'id_ruang_sub' => Auth::user()->id_ruang,
                'id_dpjp' => $request->id_dpjp,
                'id_dpjp_real' => $request->id_dpjp,
                'id_rr' => $request->id_dpjp,
                'id_tanggung' => $tanggung,
                'id_jasa' => $request->id_jasa,
                'id_pasien_jenis' => 1,
                'id_pasien_jenis_rawat' => 1,
                'tarif' => str_replace(',','',$request->tarif),
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);
        }        

        return back();
    }

    public function pasien_keluar_rincian_dpjp_export(request $request){
        $awal   = Carbon::parse($request->awal);
        $akhir  = Carbon::parse($request->akhir);

        $diff = $awal->diffInDays($akhir);

        if($diff <= 31){
            return Excel::download(new PasienRincianPerDPJP($request->awal, $request->akhir, $request->id_ruang, $request->id_dpjp), 'Rincian Pasien Per DPJP.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            Toastr::error('Periode tanggal tidak boleh lebih dari 31 hari.');
            return back();
        }        
    }

    public function pasien_per_ruang(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        if($request->id_dpjp){
            $id_dpjp   = $request->id_dpjp;
        } else {
            $id_dpjp   = '';
        }

        if($request->id_jenis){
            $id_jenis   = $request->id_jenis;
        } else {
            $id_jenis   = '';
        }

        $ruang      = DB::table('dt_ruang')
                        ->where('hapus',0)                        
                        ->orderby('ruang')
                        ->get();

        $dpjp   = User::leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

        $tarif     = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->selectRaw('SUM(IF(dt_pasien.id_jenis = 1, dt_pasien_layanan.tarif,0)) as rajal_umum,
                                     SUM(IF(dt_pasien.id_jenis = 2, dt_pasien_layanan.tarif,0)) as rajal_jkn,
                                     SUM(IF(dt_pasien.id_jenis = 3, dt_pasien_layanan.tarif,0)) as ranap_umum,
                                     SUM(IF(dt_pasien.id_jenis = 4, dt_pasien_layanan.tarif,0)) as ranap_jkn')

                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang)
                                         ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_jenis',$id_jenis);
                        })

                        ->first();

        $rajal_umum = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)
                        ->where('dt_pasien.id_jenis',1)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_ruang.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_jenis',$id_jenis);
                        })

                        ->count();

        $rajal_jkn  = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)
                        ->where('dt_pasien.id_jenis',2)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_ruang.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_jenis',$id_jenis);
                        })

                        ->count();

        $ranap_umum = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)
                        ->where('dt_pasien.id_jenis',3)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_ruang.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_jenis',$id_jenis);
                        })

                        ->count();

        $ranap_jkn  = DB::table('dt_pasien_ruang')
                        ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)
                        ->where('dt_pasien.id_jenis',4)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_ruang.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien.id_jenis',$id_jenis);
                        })

                        ->count();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            $pasien     = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d %M %Y") as waktu,
                                     DATE_FORMAT(dt_pasien.keluar, "%d %M %Y") as keluar,
                                     dt_pasien_layanan.id_ruang,
                                     dt_pasien_layanan.id_ruang_sub,
                                     dt_pasien_layanan.tarif,
                                     dt_ruang.ruang,
                                     dt_pasien.nama as pasien,
                                     LPAD(dt_pasien.register,4,0) as register,
                                     dt_pasien.no_mr,
                                     dt_jasa.jasa,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     dt_pasien_jenis.jenis as jenis_pasien')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang)
                                         ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_jenis',$id_jenis);
                        })

                        ->paginate(10);

            return view('mobile.pasien_per_ruang',compact('pasien','awal','akhir','id_ruang','ruang','id_dpjp','dpjp','jenis','id_jenis','tarif','rajal_umum','rajal_jkn','ranap_umum','ranap_jkn'));
        } else {
            $pasien     = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d %M %Y") as waktu,
                                     DATE_FORMAT(dt_pasien.keluar, "%d %M %Y") as keluar,
                                     dt_pasien_layanan.id_ruang,
                                     dt_pasien_layanan.id_ruang_sub,
                                     dt_pasien_layanan.tarif,
                                     dt_ruang.ruang,
                                     dt_pasien.nama as pasien,
                                     LPAD(dt_pasien.register,4,0) as register,
                                     dt_pasien.no_mr,
                                     dt_jasa.jasa,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                     dt_pasien_jenis.jenis as jenis_pasien')
                        ->whereDate('dt_pasien.keluar','>=',$awal)
                        ->whereDate('dt_pasien.keluar','<=',$akhir)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang)
                                         ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang);
                        })

                        ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                        ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_jenis',$id_jenis);
                        })

                        ->get();

            return view('pasien_per_ruang',compact('pasien','awal','akhir','id_ruang','ruang','id_dpjp','dpjp','jenis','id_jenis','tarif','rajal_umum','rajal_jkn','ranap_umum','ranap_jkn'));
        }        
    }

    public function pasien_statistik(request $request){
        Carbon::setLocale('id');

        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
            $rng        = DB::table('dt_ruang')->where('id',$request->id_ruang)->first();
        } else {
            $id_ruang   = '';
            $rng        = '';
        }

        $ruang      = DB::table('dt_ruang')
                        ->where('dt_ruang.inap',1)
                        ->where('dt_ruang.hapus',0)
                        ->orwhere('dt_ruang.jalan',1)
                        ->where('dt_ruang.hapus',0)
                        ->orderby('ruang')
                        ->get();        

        $dari    = Carbon::parse($awal);
        $sampai  = Carbon::parse($akhir);

        $tgl_awal   = Carbon::parse($awal)->format('d F Y','id');
        $tgl_akhir  = Carbon::parse($akhir)->format('d F Y','id');

        $diff = $dari->diffInDays($sampai);

        if($diff <= 31){
            $kalkulasi  = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->selectRaw('SUM(IF(dt_pasien.id_pasien_jenis = 1 AND dt_pasien.id_pasien_jenis_rawat = 1,1,0)) as rju,
                                         SUM(IF(dt_pasien.id_pasien_jenis > 1 AND dt_pasien.id_pasien_jenis_rawat = 1,1,0)) as rjj,
                                         SUM(IF(dt_pasien.id_pasien_jenis = 1 AND dt_pasien.id_pasien_jenis_rawat = 2,1,0)) as riu,
                                         SUM(IF(dt_pasien.id_pasien_jenis > 1 AND dt_pasien.id_pasien_jenis_rawat = 2,1,0)) as rij')
                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien_ruang.id_ruang',$id_ruang)
                            ->first();

            $tarif_kal = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_layanan','dt_pasien_ruang.id','=','dt_pasien_layanan.id_pasien_ruang')
                            ->selectRaw('SUM(IF(dt_pasien.id_pasien_jenis = 1 AND dt_pasien.id_pasien_jenis_rawat = 1,dt_pasien_layanan.tarif,0)) as tarif_rju,
                                         SUM(IF(dt_pasien.id_pasien_jenis > 1 AND dt_pasien.id_pasien_jenis_rawat = 1,dt_pasien_layanan.tarif,0)) as tarif_rjj,
                                         SUM(IF(dt_pasien.id_pasien_jenis = 1 AND dt_pasien.id_pasien_jenis_rawat = 2,dt_pasien_layanan.tarif,0)) as tarif_riu,
                                         SUM(IF(dt_pasien.id_pasien_jenis > 1 AND dt_pasien.id_pasien_jenis_rawat = 2,dt_pasien_layanan.tarif,0)) as tarif_rij')
                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien_layanan.id_ruang',$id_ruang)
                            ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang)
                            ->first();

            $rju        = DB::table('dt_pasien_layanan')                  
                            ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien.nama,
                                         dt_pasien_jenis.jenis as jenis_pasien,
                                         CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                         dt_pasien.no_mr,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_jasa.jasa,
                                         dt_pasien_layanan.tarif')

                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien.id_pasien_jenis',1)
                            ->where('dt_pasien.id_pasien_jenis_rawat',1)
                            ->where('dt_pasien_layanan.id_ruang',$id_ruang)
                            ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang)
                            ->get();

            $rjj        = DB::table('dt_pasien_layanan')                  
                            ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien.nama,
                                         dt_pasien_jenis.jenis as jenis_pasien,
                                         CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                         dt_pasien.no_mr,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_jasa.jasa,
                                         dt_pasien_layanan.tarif')

                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien.id_pasien_jenis','>',1)
                            ->where('dt_pasien.id_pasien_jenis_rawat',1)
                            ->where('dt_pasien_layanan.id_ruang',$id_ruang)
                            ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang)
                            ->get();

            $riu        = DB::table('dt_pasien_layanan')                  
                            ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien.nama,
                                         dt_pasien_jenis.jenis as jenis_pasien,
                                         CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                         dt_pasien.no_mr,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_jasa.jasa,
                                         dt_pasien_layanan.tarif')

                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien.id_pasien_jenis',1)
                            ->where('dt_pasien.id_pasien_jenis_rawat',2)
                            ->where('dt_pasien_layanan.id_ruang',$id_ruang)
                            ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang)
                            ->get();

            $rij        = DB::table('dt_pasien_layanan')                  
                            ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                            ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                            ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                            ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien.nama,
                                         dt_pasien_jenis.jenis as jenis_pasien,
                                         CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                         dt_pasien.no_mr,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_jasa.jasa,
                                         dt_pasien_layanan.tarif')

                            ->whereNotNull('dt_pasien.keluar')
                            ->whereDate('dt_pasien.keluar','>=',$awal)
                            ->whereDate('dt_pasien.keluar','<=',$akhir)
                            ->where('dt_pasien.id_pasien_jenis','>',1)
                            ->where('dt_pasien.id_pasien_jenis_rawat',2)
                            ->where('dt_pasien_layanan.id_ruang',$id_ruang)
                            ->where('dt_pasien_layanan.id_ruang_sub',$id_ruang)
                            ->get();

            
            $agent = new Agent();

            if ($agent->isMobile()) {
              return view('mobile.statistik',compact('ruang','awal','akhir','kalkulasi','tarif_kal','id_ruang','dari','sampai','tgl_awal','tgl_akhir','rng','rju','rjj','riu','rij'));
            } else {
              return view('statistik',compact('ruang','awal','akhir','kalkulasi','tarif_kal','id_ruang','dari','sampai','tgl_awal','tgl_akhir','rng','rju','rjj','riu','rij'));
            }
        } else {
            Toastr::error('Periode tanggal tidak boleh lebih dari 31 hari.');
            return back();
        }
    }

    public function pasien_cari(request $request){
      if($request->cari){
        $cari    = $request->cari;      

      $pasien   = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i:%s") as waktu,
                                 DATE_FORMAT(dt_pasien.keluar,"%d/%m/%Y - %H:%i:%s") as keluar,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_petugas) as petugas,
                                 dt_pasien.nama AS pasien,
                                 dt_pasien.no_mr,
                                 dt_pasien_jenis.jenis as jenis_pasien,
                                 
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang_perawatan,

                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_tindakan,
                                 
                                 dt_jasa.jasa,
                                 dt_pasien_layanan.tarif,
                                 dt_pasien_layanan.js,
                                 dt_pasien_layanan.jp,
                                 dt_pasien_layanan.profit,
                                 dt_pasien_layanan.penghasil,
                                 dt_pasien_layanan.non_penghasil,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,

                                 dt_pasien_layanan.jasa_dpjp,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,

                                 dt_pasien_layanan.jasa_pengganti,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_operator) as operator,

                                 dt_pasien_layanan.jasa_operator,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,

                                 dt_pasien_layanan.jasa_anastesi,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,

                                 dt_pasien_layanan.jasa_pendamping,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,

                                 dt_pasien_layanan.jasa_konsul,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_laborat) as laborat,

                                 dt_pasien_layanan.jasa_laborat,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_tanggung) as penanggung_jawab,

                                 dt_pasien_layanan.jasa_tanggung,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_radiologi) as radiologi,

                                 dt_pasien_layanan.jasa_radiologi,

                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_rr) as rr,

                                 dt_pasien_layanan.jasa_rr,
                                 dt_pasien_layanan.medis,
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
                                 dt_pasien_layanan.admin_farmasi,
                                 dt_pasien_layanan.administrasi,
                                 dt_pasien_layanan.pos_remun,
                                 dt_pasien_layanan.direksi,
                                 dt_pasien_layanan.staf_direksi,
                                 dt_pasien_layanan.insentif_perawat')
                    ->when($cari, function ($query) use ($cari) {
                        return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%')
                                     ->orwhere('dt_pasien.no_mr',$cari);

                      })
                    ->orderby('dt_pasien_layanan.waktu')
                    ->get();
      } else {
        $cari     = '';
        $pasien   = '';
      }

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.pasien_cari',compact('cari','pasien'));
      } else {
        return view('pasien_cari',compact('cari','pasien'));
      }        
    }

    public function pasien_gizi_transaksi(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        if($request->jns){
            $jns   = $request->jns;
        } else {
            $jns   = '';
        }

        if($request->rwt){
            $rwt   = $request->rwt;
        } else {
            $rwt   = '';
        }

        $transaksi  = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users','dt_pasien_layanan.id_petugas','=','users.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak,
                                     dt_pasien.nama,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     dt_ruang.ruang,
                                     dt_pasien_layanan.tarif,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as petugas,
                                     dt_pasien_layanan.medis,
                                     dt_pasien_layanan.jp_perawat,
                                     dt_pasien_layanan.administrasi')
                        ->where('dt_pasien_layanan.id_ruang_sub',31)
                        ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                        ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)

                        ->when($jns, function ($query) use ($jns) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis',$jns);
                        })

                        ->when($rwt, function ($query) use ($rwt) {
                            return $query->where('dt_pasien_layanan.id_pasien_jenis_rawat',$rwt);
                        })

                        ->get();

        $total      = DB::table('dt_pasien_layanan')
                        ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif,
                                     SUM(dt_pasien_layanan.medis) as medis,
                                     SUM(dt_pasien_layanan.jp_perawat) as jp_perawat,
                                     SUM(dt_pasien_layanan.administrasi) as administrasi')
                        ->where('dt_pasien_layanan.id_ruang_sub',31)
                        ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                        ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)
                        ->first();

        $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

        $rawat  = DB::table('dt_pasien_jenis_rawat')->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.transaksi_dapur',compact('transaksi','awal','akhir','total','jenis','rawat','jns','rwt'));
        } else {
            return view('transaksi_dapur',compact('transaksi','awal','akhir','total','jenis','rawat','jns','rwt'));
        }        
    }

    public function pasien_operasi_transaksi(request $request){
        if($request->awal){
            $awal   = $request->awal;
        } else {
            $awal   = date("Y-m-d");
        }

        if($request->akhir){
            $akhir   = $request->akhir;
        } else {
            $akhir   = date("Y-m-d");
        }

        if($request->oper){
            $oper   = $request->oper;
        } else {
            $oper   = '';
        }

        $layanan  = DB::table('dt_pasien_layanan')
                        ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                        ->leftjoin('dt_pasien_jenis','dt_pasien.id_pasien_jenis','=','dt_pasien_jenis.id')
                        ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                        ->leftjoin('dt_ruang','dt_pasien_layanan.id_ruang','=','dt_ruang.id')
                        ->selectRaw('dt_pasien_layanan.id,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m/%Y - %H:%i") as waktu,
                                     DATE_FORMAT(dt_pasien_layanan.waktu, "%d/%m - %H:%i") as wak,
                                     dt_jasa.jasa,
                                     dt_pasien.nama,
                                     dt_pasien_jenis.jenis as jenis_pasien,
                                     dt_ruang.ruang,
                                     dt_pasien_layanan.tarif,

                                     (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                     FROM users
                                     WHERE users.id = dt_pasien_layanan.id_operator) as operator,

                                     (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                     FROM users
                                     WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,

                                     (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                     FROM users
                                     WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,
                                     
                                     dt_pasien_layanan.jasa_operator,
                                     dt_pasien_layanan.jasa_anastesi,
                                     dt_pasien_layanan.jasa_pendamping,
                                     dt_pasien_layanan.pen_anastesi,
                                     dt_pasien_layanan.per_asisten_1,
                                     dt_pasien_layanan.per_asisten_2,
                                     dt_pasien_layanan.instrumen,
                                     dt_pasien_layanan.sirkuler,
                                     dt_pasien_layanan.administrasi')
                        ->where('dt_pasien_layanan.id_ruang_sub',5)
                        ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                        ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)

                        ->when($oper, function ($query) use ($oper) {
                            return $query->where('dt_pasien_layanan.id_operator',$oper);
                        })

                        ->get();

        $operator   = DB::table('users')
                    ->where('users.id_tenaga_bagian',23)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.transaksi_operasi',compact('layanan','awal','akhir','operator','oper'));
        } else {
            return view('transaksi_operasi',compact('layanan','awal','akhir','operator','oper'));
        }        
    }

    public function pasien_perawatan_data(request $request){
      if($request->cari){
        $cari   = $request->cari;
      } else {
        $cari   = '';
      }

      if($request->tampil){
        $tampil   = $request->tampil;
      } else {
        $tampil   = 10;
      }

      if($request->id_pasien_jenis){
        $id_pasien_jenis   = $request->id_pasien_jenis;
      } else {
        $id_pasien_jenis   = '';
      }

      if($request->id_pasien_jenis_rawat){
        $id_pasien_jenis_rawat   = $request->id_pasien_jenis_rawat;
      } else {
        $id_pasien_jenis_rawat   = '';
      }

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_dpjp){
        $id_dpjp   = $request->id_dpjp;
      } else {
        $id_dpjp   = '';
      }

      $jenis  = DB::table('dt_pasien_jenis')
                  ->selectRaw('dt_pasien_jenis.id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_pasien_jenis.hapus',0)
                  ->get();

      $ruang  = DB::table('dt_ruang')
                  ->where('dt_ruang.inap',1)
                  ->where('dt_ruang.hapus',0)
                  ->orwhere('dt_ruang.jalan',1)
                  ->where('dt_ruang.hapus',0)
                  ->orderby('dt_ruang.ruang')
                  ->get();

      $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      $pasien   = DB::table('dt_pasien_ruang')
                    ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('dt_pasien_jenis_rawat','dt_pasien_ruang.id_pasien_jenis_rawat','=','dt_pasien_jenis_rawat.id')
                    ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id')
                    ->where('dt_pasien_ruang.stat',0)
                    ->selectRaw('dt_pasien_ruang.id,
                                 CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                 dt_pasien.tgl_data,
                                 dt_pasien.nama,
                                 dt_pasien.alamat,
                                 dt_pasien.umur_thn,
                                 dt_pasien.umur_bln,
                                 dt_pasien.id_kelamin,                                 
                                 LPAD(dt_pasien.no_mr,10,0) as no_mr,
                                 dt_pasien_jenis.jenis,
                                 dt_pasien_jenis_rawat.jenis_rawat,
                                 dt_ruang.ruang,
                                 dt_pasien_ruang.id_pasien,
                                 (SELECT
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_ruang.id_dpjp) as dpjp,
                                 DATE_FORMAT(dt_pasien.masuk,"%d %M %Y") as masuk')
                    ->when($id_pasien_jenis, function ($query) use ($id_pasien_jenis) {
                        return $query->where('dt_pasien_ruang.id_pasien_jenis',$id_pasien_jenis);
                      })
                    ->when($id_pasien_jenis_rawat, function ($query) use ($id_pasien_jenis_rawat) {
                        return $query->where('dt_pasien_ruang.id_pasien_jenis_rawat',$id_pasien_jenis_rawat);
                      })
                    ->when($id_ruang, function ($query) use ($id_ruang) {
                        return $query->where('dt_pasien_ruang.id_ruang',$id_ruang);
                      })
                    ->when($id_dpjp, function ($query) use ($id_dpjp) {
                        return $query->where('dt_pasien_ruang.id_dpjp',$id_dpjp);
                      })
                    ->when($cari, function ($query) use ($cari) {
                        return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%');
                      })
                    ->paginate($tampil);

      return view('pasien_perawatan_data',compact('jenis','ruang','id_pasien_jenis','id_pasien_jenis_rawat','id_ruang','dpjp','id_dpjp','pasien','tampil','cari'));
    }

    public function pasien_perawatan_data_detil($id){
      $pasien   = DB::table('dt_pasien')
                    ->where('dt_pasien.id',Crypt::decrypt($id))
                    ->selectRaw('dt_pasien.id,
                                 CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                 dt_pasien.nama,
                                 dt_pasien.alamat,
                                 dt_pasien.umur_thn,
                                 dt_pasien.umur_bln,
                                 IF(dt_pasien.id_kelamin = 1,"LAKI-LAKI","PEREMPUAN") as kelamin,
                                 LPAD(dt_pasien.no_mr,8,0) as no_mr,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_pasien.id_pasien_jenis) as jenis,
                                 DATE_FORMAT(dt_pasien.masuk,"%d %M %Y - %H:%i") as masuk,
                                 dt_pasien.tagihan,
                                 (SELECT SUM(dt_pasien_layanan.tarif)
                                  FROM dt_pasien_layanan
                                  WHERE dt_pasien_layanan.id_pasien = dt_pasien.id) as tagihan')
                    ->first();

      $layanan  = DB::table('dt_pasien_layanan')
                    ->where('dt_pasien_layanan.id_pasien',Crypt::decrypt($id))
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien_layanan.waktu,
                                 dt_pasien_layanan.id_pasien,
                                 dt_pasien_layanan.id_pasien_jenis,
                                 dt_pasien_layanan.id_pasien_jenis_rawat,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_tindakan,
                                 (SELECT dt_jasa.jasa
                                  FROM dt_jasa
                                  WHERE dt_jasa.id = dt_pasien_layanan.id_jasa) as jasa,

                                 dt_pasien_layanan.tarif,
                                 dt_pasien_layanan.js,
                                 dt_pasien_layanan.jp,
                                 dt_pasien_layanan.profit,
                                 dt_pasien_layanan.penghasil,
                                 dt_pasien_layanan.non_penghasil,
                                 dt_pasien_layanan.id_dpjp,
                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,

                                  (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,
                                 dt_pasien_layanan.jasa_dpjp,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,
                                 dt_pasien_layanan.jasa_pengganti,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                 dt_pasien_layanan.jasa_operator,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,
                                 dt_pasien_layanan.jasa_anastesi,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,
                                 dt_pasien_layanan.jasa_pendamping,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                 dt_pasien_layanan.jasa_konsul,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_laborat) as laborat,
                                 dt_pasien_layanan.jasa_laborat,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung,
                                 dt_pasien_layanan.jasa_tanggung,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_radiologi) as radiologi,
                                 dt_pasien_layanan.jasa_radiologi,

                                 (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
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
                                 dt_pasien_layanan.fisio,
                                 dt_pasien_layanan.apoteker,
                                 dt_pasien_layanan.ass_apoteker,
                                 dt_pasien_layanan.admin_farmasi,
                                 dt_pasien_layanan.administrasi,
                                 dt_pasien_layanan.pemulasaran')
                    ->get();

      return view('pasien_perawatan_data_detil',compact('pasien','layanan'));
    }

    public function pasien_layanan_data(request $request){
      if($request->cari){
        $cari    = $request->cari;
      } else {
        $cari    = '';
      }

      if($request->tampil){
        $tampil    = $request->tampil;
      } else {
        $tampil    = 10;
      }

      if($request->awal){
        $awal    = $request->awal;
      } else {
        $awal    = date("Y-m-d");
      }

      if($request->akhir){
        $akhir    = $request->akhir;
      } else {
        $akhir    = date("Y-m-d");
      }

      if($request->id_pasien_jenis){
        $id_pasien_jenis   = $request->id_pasien_jenis;
      } else {
        $id_pasien_jenis   = '';
      }

      if($request->id_pasien_jenis_rawat){
        $id_pasien_jenis_rawat   = $request->id_pasien_jenis_rawat;
      } else {
        $id_pasien_jenis_rawat   = '';
      }

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_dpjp){
        $id_dpjp   = $request->id_dpjp;
      } else {
        $id_dpjp   = '';
      }

      $jenis  = DB::table('dt_pasien_jenis')
                  ->selectRaw('dt_pasien_jenis.id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_pasien_jenis.hapus',0)
                  ->get();

      $ruang  = DB::table('dt_ruang')
                  ->where('dt_ruang.inap',1)
                  ->where('dt_ruang.hapus',0)
                  ->orwhere('dt_ruang.jalan',1)
                  ->where('dt_ruang.hapus',0)
                  ->orderby('dt_ruang.ruang')
                  ->get();

      $dpjp   = DB::table('users')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->where('users_tenaga_bagian.medis',1)
                    ->where('users.hapus',0)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->orderby('users.nama')
                    ->get();

      $layanan  = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('dt_pasien_jenis_rawat','dt_pasien_layanan.id_pasien_jenis_rawat','=','dt_pasien_jenis_rawat.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien.nama,
                                 DATE_FORMAT(dt_pasien_layanan.waktu,"%d/%m/%Y - %H:%i") as waktu,
                                 dt_pasien_jenis.jenis,
                                 dt_pasien_jenis_rawat.jenis_rawat,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang_rawat,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_tindakan,
                                 dt_jasa.jasa,
                                 dt_pasien_layanan.tarif,
                                 dt_pasien_layanan.js,
                                 dt_pasien_layanan.jp,
                                 dt_pasien_layanan.profit,
                                 dt_pasien_layanan.penghasil,
                                 dt_pasien_layanan.non_penghasil,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp) as dpjp,
                                  (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_dpjp_real) as dpjp_real,
                                 dt_pasien_layanan.jasa_dpjp,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pengganti) as pengganti,
                                 dt_pasien_layanan.jasa_pengganti,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_operator) as operator,
                                 dt_pasien_layanan.jasa_operator,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_anastesi) as anastesi,
                                 dt_pasien_layanan.jasa_anastesi,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_pendamping) as pendamping,
                                 dt_pasien_layanan.jasa_pendamping,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_konsul) as konsul,
                                 dt_pasien_layanan.jasa_konsul,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_laborat) as laborat,
                                 dt_pasien_layanan.jasa_laborat,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_tanggung) as tanggung,
                                 dt_pasien_layanan.jasa_tanggung,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = dt_pasien_layanan.id_radiologi) as radiologi,
                                 dt_pasien_layanan.jasa_radiologi,
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
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
                                 dt_pasien_layanan.admin_farmasi,
                                 dt_pasien_layanan.administrasi,
                                 dt_pasien_layanan.pos_remun,
                                 dt_pasien_layanan.direksi,
                                 dt_pasien_layanan.staf_direksi,
                                 dt_pasien_layanan.fisio,
                                 dt_pasien_layanan.pemulasaran')
                    ->whereDate('dt_pasien_layanan.waktu','>=',$awal)
                    ->whereDate('dt_pasien_layanan.waktu','<=',$akhir)
                    
                    ->when($id_pasien_jenis, function ($query) use ($id_pasien_jenis) {
                        return $query->where('dt_pasien_layanan.id_pasien_jenis',$id_pasien_jenis);
                      })

                    ->when($id_pasien_jenis_rawat, function ($query) use ($id_pasien_jenis_rawat) {
                        return $query->where('dt_pasien_layanan.id_pasien_jenis_rawat',$id_pasien_jenis_rawat);
                      })

                    ->when($id_ruang, function ($query) use ($id_ruang) {
                        return $query->where('dt_pasien_layanan.id_ruang',$id_ruang);
                      })

                    ->when($id_dpjp, function ($query) use ($id_dpjp) {
                        return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                      })

                    ->when($cari, function ($query) use ($cari) {
                        return $query->where('dt_pasien.nama','LIKE','%'.$cari.'%');
                      })

                    ->paginate($tampil);


      return view('pasien_layanan_data',compact('jenis','ruang','id_pasien_jenis','id_pasien_jenis_rawat','id_ruang','dpjp','id_dpjp','awal','akhir','layanan','tampil','cari'));
    }
}