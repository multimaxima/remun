<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\dt_pasien_ruang;
use App\dt_pasien_layanan;
use App\Exports\ClaimBPJS;
use Excel;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class BPJSController extends Controller
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
    public function bpjs_admin(request $request){
      $cek    = DB::table('control')->first();

      $jenis  = DB::table('dt_pasien_jenis')
                    ->where('dt_pasien_jenis.id','>',1)
                    ->where('dt_pasien_jenis.hapus',0)
                    ->get();

      $bpjs    = DB::table('dt_claim_bpjs_stat')
                    ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 dt_pasien_jenis.jenis,
                                 DATE_FORMAT(dt_claim_bpjs_stat.created_at, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 
                                 (SELECT SUM(dt_claim_bpjs.nominal_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_inap,

                                 (SELECT SUM(dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_inap,

                                 (SELECT SUM(dt_claim_bpjs.nominal_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_jalan,

                                 (SELECT SUM(dt_claim_bpjs.claim_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_jalan')

                    ->where('dt_claim_bpjs_stat.stat','<',2)
                    ->where('dt_claim_bpjs_stat.hapus',0)
                    ->orderby('dt_claim_bpjs_stat.id','desc')
                    ->get();

      return view('bpjs_admin',compact('bpjs','cek','jenis'));
    }

    public function bpjs($id){
        $cek    = DB::table('control')->first();

        $bpjs   = DB::table('dt_claim_bpjs_stat')
                    ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->where('dt_claim_bpjs_stat.id',Crypt::decrypt($id))
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 dt_claim_bpjs_stat.awal,
                                 dt_claim_bpjs_stat.akhir,
                                 dt_claim_bpjs_stat.jasa_awal,
                                 dt_claim_bpjs_stat.jasa_akhir,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal,"%d %M %Y") as periode_awal,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir,"%d %M %Y") as periode_akhir,
                                 DATE_FORMAT(dt_claim_bpjs_stat.jasa_awal,"%d %b %Y") as layanan_awal,
                                 DATE_FORMAT(dt_claim_bpjs_stat.jasa_akhir,"%d %b %Y") as layanan_akhir,
                                 dt_claim_bpjs_stat.id_pasien_jenis,
                                 dt_pasien_jenis.jenis,
                                 TIMEDIFF(dt_claim_bpjs_stat.selesai,dt_claim_bpjs_stat.mulai) as waktu,
                                 dt_claim_bpjs_stat.stat')
                    ->first();

        $jenis  = DB::table('dt_pasien_jenis')
                    ->where('dt_pasien_jenis.id','>',1)
                    ->where('dt_pasien_jenis.hapus',0)
                    ->get();

          $detil    = DB::table('dt_claim_bpjs')
                        ->leftjoin('users','dt_claim_bpjs.id_dpjp','=','users.id')
                        ->selectRaw('dt_claim_bpjs.id,
                                     dt_claim_bpjs.waktu,
                                     dt_claim_bpjs.dari,
                                     dt_claim_bpjs.sampai,
                                     dt_claim_bpjs.id_dpjp,
                                 
                                     dt_claim_bpjs.nominal_inap,
                                     dt_claim_bpjs.sisa_sebelum_inap,
                                     dt_claim_bpjs.jumlah_inap,
                                     dt_claim_bpjs.claim_inap,
                                     dt_claim_bpjs.sisa_inap,
                                     dt_claim_bpjs.medis_inap,
                                     (dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap as claim_medis_inap,

                                     dt_claim_bpjs.nominal_jalan,
                                     dt_claim_bpjs.sisa_sebelum_jalan,
                                     dt_claim_bpjs.jumlah_jalan,
                                     dt_claim_bpjs.claim_jalan,
                                     dt_claim_bpjs.sisa_jalan,
                                     dt_claim_bpjs.medis_jalan,
                                     (dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan as claim_medis_jalan,

                                     IFNULL(((dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap),0) +
                                     IFNULL(((dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan),0) as total_medis,
                                     dt_claim_bpjs.stat,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                     users.gelar_depan,
                                     users.gelar_belakang')
                        ->where('dt_claim_bpjs.id_stat',$bpjs->id)
                        ->orderby('users.nama')
                        ->get();

          $awal     = $bpjs->awal;
          $akhir    = $bpjs->akhir;
          $id_jenis = $bpjs->id_pasien_jenis;

          $tag      = DB::table('dt_claim_bpjs')
                        ->selectRaw('SUM(IF(nominal_inap > 0,nominal_inap,0)) as t_inap,
                                     SUM(IF(nominal_jalan > 0,nominal_jalan,0)) as t_jalan,
                                     SUM(IF(medis_inap > 0,medis_inap,0)) as m_inap,
                                     SUM((dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap) as cm_inap,
                                     SUM(IF(medis_jalan > 0,medis_jalan,0)) as m_jalan,
                                     SUM(IF(claim_inap > 0,claim_inap,0)) as c_inap,
                                     SUM(IF(claim_jalan > 0,claim_jalan,0)) as c_jalan,
                                     SUM((dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan) as cm_jalan')
                        ->where('dt_claim_bpjs.id_stat',$bpjs->id)
                        ->first();
        
        $agent = new Agent();
        
        if ($agent->isMobile()) {
          return view('mobile.bpjs',compact('bpjs','detil','awal','akhir','tag','cek','jenis','id_jenis'));
        } else {
          return view('bpjs',compact('bpjs','detil','awal','akhir','tag','cek','jenis','id_jenis'));
        }        
    }

    public function ambil_data(request $request){
        $cek    = DB::table('control')->first();

        if($cek->bpjs == 0 && $cek->remun == 0 && $cek->kalkulasi_jasa == 0 && $request->awal <> $request->akhir && $request->id_jenis){
          DB::table('control')
            ->where('id',$cek->id)
            ->update([
              'bpjs' => 1,
            ]);

          DB::table('dt_claim_bpjs_stat')
            ->insert([
              'awal' => $request->awal,
              'akhir' => $request->akhir,
              'id_pasien_jenis' => $request->id_jenis,
              'mulai' => now(),
              'petugas_create' => Auth::user()->id,
              'petugas_update' => Auth::user()->id,
            ]);

          $bpjs   = DB::table('dt_claim_bpjs_stat')
                      ->selectRaw('dt_claim_bpjs_stat.id,
                                   dt_claim_bpjs_stat.id_pasien_jenis,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal, "%Y-%m-%d") as awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%Y-%m-%d") as akhir')
                      ->where('dt_claim_bpjs_stat.stat',0)
                      ->where('dt_claim_bpjs_stat.hapus',0)
                      ->orderby('dt_claim_bpjs_stat.id','desc')
                      ->first();

          DB::select('CALL bpjs_hitung('.$bpjs->id.',"'.$bpjs->awal.'","'.$bpjs->akhir.'","'.$bpjs->id_pasien_jenis.'");');

          return back();
        } else {
          return back()->with('gagal','Terjadi kesalahan input.');
        }
    }

    public function bpjs_batal($id){
      DB::table('dt_claim_bpjs_stat')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);      

      return redirect()->route('bpjs_admin')->with('success','Data claim asuransi berhasil dibatalkan.');
    }

    public function bpjs_seimbang($id){        
      DB::table('dt_claim_bpjs')
          ->where('id_stat',Crypt::decrypt($id))
          ->update([
            'claim_inap' => DB::raw('dt_claim_bpjs.nominal_inap'),
            'petugas_update' => Auth::user()->id,
          ]);

      DB::table('dt_claim_bpjs')
          ->where('id_stat',Crypt::decrypt($id))
          ->update([
            'claim_jalan' => DB::raw('dt_claim_bpjs.nominal_jalan'),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->back()->with('success','Data claim seimbang berhasil dilakukan.');
    }

    public function bpjs_salin_inap($id){        
        DB::table('dt_claim_bpjs')
          ->where('id_stat',Crypt::decrypt($id))
          ->update([
            'claim_inap' => DB::raw('dt_claim_bpjs.nominal_inap'),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->back()->with('success','Data claim rawat inap berhasil disimpan.');
    }

    public function bpjs_salin_jalan($id){
        DB::table('dt_claim_bpjs')
          ->where('id_stat',Crypt::decrypt($id))
          ->update([
            'claim_jalan' => DB::raw('dt_claim_bpjs.nominal_jalan'),
            'petugas_update' => Auth::user()->id,
          ]);

        return redirect()->back()->with('success','Data claim rawat jalan berhasil disimpan.');
    }

    public function bpjs_data(){
      $agent = new Agent();
        
      if ($agent->isMobile()) {
        $bpjs    = DB::table('dt_claim_bpjs_stat')
                    ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 dt_pasien_jenis.jenis,
                                 DATE_FORMAT(dt_claim_bpjs_stat.created_at, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 
                                 (SELECT SUM(dt_claim_bpjs.nominal_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_inap,

                                 (SELECT SUM(dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_inap,

                                 (SELECT SUM(dt_claim_bpjs.nominal_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_jalan,

                                 (SELECT SUM(dt_claim_bpjs.claim_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_jalan')

                    ->where('dt_claim_bpjs_stat.stat','>',1)
                    ->where('dt_claim_bpjs_stat.hapus',0)
                    ->orderby('dt_claim_bpjs_stat.id','desc')
                    ->paginate(10);

        return view('mobile.bpjs_data',compact('bpjs'));
      } else {
        $bpjs    = DB::table('dt_claim_bpjs_stat')
                    ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 dt_pasien_jenis.jenis,
                                 DATE_FORMAT(dt_claim_bpjs_stat.created_at, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 
                                 (SELECT SUM(dt_claim_bpjs.nominal_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_inap,

                                 (SELECT SUM(dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_inap,

                                 (SELECT SUM(dt_claim_bpjs.nominal_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as nominal_jalan,

                                 (SELECT SUM(dt_claim_bpjs.claim_jalan)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim_jalan')

                    ->where('dt_claim_bpjs_stat.stat','>',1)
                    ->where('dt_claim_bpjs_stat.hapus',0)
                    ->orderby('dt_claim_bpjs_stat.id','desc')
                    ->get();

        return view('bpjs_data',compact('bpjs'));
      }     
    }

    public function bpjs_data_detil($id){
        $bpjs    = DB::table('dt_claim_bpjs_stat')
                      ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                      ->where('dt_claim_bpjs_stat.id',Crypt::decrypt($id))
                      ->selectRaw('dt_claim_bpjs_stat.id,
                                   dt_pasien_jenis.jenis,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as akhir,
                                   DATE_FORMAT(dt_claim_bpjs_stat.jasa_awal,"%d %b %Y") as layanan_awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.jasa_akhir,"%d %b %Y") as layanan_akhir')
                      ->first();      

        $tag      = DB::table('dt_claim_bpjs')
                      ->selectRaw('SUM(IF(nominal_inap > 0,nominal_inap,0)) as t_inap,
                                   SUM(IF(nominal_jalan > 0,nominal_jalan,0)) as t_jalan,
                                   SUM(IF(medis_inap > 0,medis_inap,0)) as m_inap,
                                   SUM((dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap) as cm_inap,
                                   SUM(IF(medis_jalan > 0,medis_jalan,0)) as m_jalan,
                                   SUM(IF(claim_inap > 0,claim_inap,0)) as c_inap,
                                   SUM(IF(claim_jalan > 0,claim_jalan,0)) as c_jalan,
                                   SUM((dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan) as cm_jalan')
                      ->where('dt_claim_bpjs.id_stat',Crypt::decrypt($id))
                      ->first();

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        $detil   = DB::table('dt_claim_bpjs')
                    ->leftjoin('users','dt_claim_bpjs.id_dpjp','=','users.id')
                    ->selectRaw('dt_claim_bpjs.id,
                                 dt_claim_bpjs.waktu,
                                 dt_claim_bpjs.dari,
                                 dt_claim_bpjs.sampai,
                                 dt_claim_bpjs.id_dpjp,
                                 
                                 dt_claim_bpjs.nominal_inap,
                                 dt_claim_bpjs.sisa_sebelum_inap,
                                 dt_claim_bpjs.jumlah_inap,
                                 dt_claim_bpjs.claim_inap,
                                 dt_claim_bpjs.sisa_inap,
                                 dt_claim_bpjs.medis_inap,

                                 dt_claim_bpjs.medis_inap * (dt_claim_bpjs.claim_inap / dt_claim_bpjs.nominal_inap) as claim_medis_inap,

                                 dt_claim_bpjs.nominal_jalan,
                                 dt_claim_bpjs.sisa_sebelum_jalan,
                                 dt_claim_bpjs.jumlah_jalan,
                                 dt_claim_bpjs.claim_jalan,
                                 dt_claim_bpjs.sisa_jalan,
                                 dt_claim_bpjs.medis_jalan,

                                 dt_claim_bpjs.medis_jalan * (dt_claim_bpjs.claim_jalan / dt_claim_bpjs.nominal_jalan) as claim_medis_jalan,

                                 (dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap +
                                 (dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan as total_medis,
                                 dt_claim_bpjs.stat,
                                 users.gelar_depan,
                                 users.gelar_belakang,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->where('dt_claim_bpjs.id_stat',Crypt::decrypt($id))
                    ->orderby('users.nama')
                    ->paginate(10);

        return view('mobile.bpjs_data_detil',compact('bpjs','detil','tag'));
      } else {
        $detil   = DB::table('dt_claim_bpjs')
                    ->leftjoin('users','dt_claim_bpjs.id_dpjp','=','users.id')
                    ->selectRaw('dt_claim_bpjs.id,
                                 dt_claim_bpjs.waktu,
                                 dt_claim_bpjs.dari,
                                 dt_claim_bpjs.sampai,
                                 dt_claim_bpjs.id_dpjp,
                                 
                                 dt_claim_bpjs.nominal_inap,
                                 dt_claim_bpjs.sisa_sebelum_inap,
                                 dt_claim_bpjs.jumlah_inap,
                                 dt_claim_bpjs.claim_inap,
                                 dt_claim_bpjs.sisa_inap,
                                 dt_claim_bpjs.medis_inap,

                                 dt_claim_bpjs.medis_inap * (dt_claim_bpjs.claim_inap / dt_claim_bpjs.nominal_inap) as claim_medis_inap,

                                 dt_claim_bpjs.nominal_jalan,
                                 dt_claim_bpjs.sisa_sebelum_jalan,
                                 dt_claim_bpjs.jumlah_jalan,
                                 dt_claim_bpjs.claim_jalan,
                                 dt_claim_bpjs.sisa_jalan,
                                 dt_claim_bpjs.medis_jalan,

                                 dt_claim_bpjs.medis_jalan * (dt_claim_bpjs.claim_jalan / dt_claim_bpjs.nominal_jalan) as claim_medis_jalan,

                                 (dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap +
                                 (dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan as total_medis,
                                 dt_claim_bpjs.stat,
                                 users.gelar_depan,
                                 users.gelar_belakang,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->where('dt_claim_bpjs.id_stat',Crypt::decrypt($id))
                    ->orderby('users.nama')
                    ->get();

        return view('bpjs_data_detil',compact('bpjs','detil','tag'));
      }     
    }

    public function bpjs_data_hapus($id){
      DB::table('dt_claim_bpjs_stat')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);      

      return redirect()->back()->with('success','Data claim asuransi berhasil dihapus.');
    }

    public function bpjs_claim(request $request){
        DB::table('dt_claim_bpjs')
            ->where('id',$request->id)
            ->update([
                'claim_jalan' => str_replace(',','',$request->claim_jalan),
                'claim_inap' => str_replace(',','',$request->claim_inap),
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data claim asuransi berhasil disimpan.');
    }

    public function bpjs_ok($id){
      $cek      = DB::table('dt_claim_bpjs')
                      ->selectRaw('SUM(IF(claim_inap > 0,claim_inap,0)) + SUM(IF(claim_jalan > 0,claim_jalan,0)) as cek')
                      ->where('dt_claim_bpjs.id_stat',Crypt::decrypt($id))
                      ->first();

      if($cek->cek > 0){
        DB::table('dt_claim_bpjs')
          ->where('id_stat',Crypt::decrypt($id))
          ->update([
            'stat' => 1,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_claim_bpjs_stat')
          ->where('id',Crypt::decrypt($id))
          ->update([
            'stat' => 2,
            'petugas_update' => Auth::user()->id,
          ]);

        return back();
      } else {
        return redirect()->back()->with('error','Anda belum memasukkan nilai claim.');
      }
      
    }

    public function bpjs_rincian($id){
        $detil    = DB::table('dt_claim_bpjs')
                      ->where('dt_claim_bpjs.id',Crypt::decrypt($id))
                      ->first();

        $bpjs     = DB::table('dt_claim_bpjs_stat')
                      ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                      ->where('dt_claim_bpjs_stat.id',$detil->id_stat)
                      ->selectRaw('dt_claim_bpjs_stat.awal,
                                   dt_claim_bpjs_stat.akhir,
                                   dt_claim_bpjs_stat.id_pasien_jenis,
                                   dt_pasien_jenis.jenis,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal,"%d %M %Y") as t_awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir,"%d %M %Y") as t_akhir')
                      ->first();

        $user   = DB::table('users')
                    ->where('id',$detil->id_dpjp)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->first();               

        $jasa           = DB::table('dt_pasien_layanan')
                            ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif,
                                         SUM(dt_pasien_layanan.jasa_dpjp) as dpjp,
                                         SUM(dt_pasien_layanan.jasa_pengganti) as pengganti,
                                         SUM(dt_pasien_layanan.jasa_operator) as operator,
                                         SUM(dt_pasien_layanan.jasa_anastesi) as anastesi,
                                         SUM(dt_pasien_layanan.jasa_pendamping) as pendamping,
                                         SUM(dt_pasien_layanan.jasa_konsul) as konsul,
                                         SUM(dt_pasien_layanan.jasa_laborat) as laborat,
                                         SUM(dt_pasien_layanan.jasa_tanggung) as tanggung,
                                         SUM(dt_pasien_layanan.jasa_radiologi) as radiologi,
                                         SUM(dt_pasien_layanan.jasa_rr) as rr,
                                         SUM(dt_pasien_layanan.jasa_dpjp) +
                                         SUM(dt_pasien_layanan.jasa_pengganti) +
                                         SUM(dt_pasien_layanan.jasa_operator) +
                                         SUM(dt_pasien_layanan.jasa_anastesi) +
                                         SUM(dt_pasien_layanan.jasa_pendamping) +
                                         SUM(dt_pasien_layanan.jasa_konsul) +
                                         SUM(dt_pasien_layanan.jasa_laborat) +
                                         SUM(dt_pasien_layanan.jasa_tanggung) +
                                         SUM(dt_pasien_layanan.jasa_radiologi) +
                                         SUM(dt_pasien_layanan.jasa_rr) as medis')

                            ->where('dt_pasien_layanan.id_dpjp',$detil->id_dpjp)
                            ->where('dt_pasien_layanan.id_pasien_jenis',$bpjs->id_pasien_jenis)
                            ->whereNotNull('dt_pasien_layanan.keluar')
                            ->whereDate('dt_pasien_layanan.keluar','>=',$bpjs->awal)
                            ->whereDate('dt_pasien_layanan.keluar','<=',$bpjs->akhir)                            
                            ->first();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          $rincian        = DB::table('dt_pasien_ruang')
                            ->leftjoin('dt_pasien','dt_pasien_ruang.id_pasien','=','dt_pasien.id') 
                            ->leftjoin('dt_ruang','dt_pasien_ruang.id_ruang','=','dt_ruang.id') 
                            ->leftjoin('users','dt_pasien_ruang.id_dpjp','=','users.id') 
                            ->leftjoin('dt_pasien_jenis','dt_pasien_ruang.id_pasien_jenis','=','dt_pasien_jenis.id') 
                            ->selectRaw('dt_pasien_ruang.id,
                                         dt_pasien_ruang.id_pasien,
                                         dt_ruang.ruang,
                                         CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                         IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                         dt_pasien.nama as pasien,
                                         dt_pasien_jenis.jenis as jenis_pasien,
                                         dt_pasien.no_mr,
                                         CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register')
                            ->with(array(
                              'pasien_layanan' => function($layanan){
                                $layanan->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_pasien_layanan.id,
                                             dt_pasien_layanan.id_pasien_ruang,
                                             dt_pasien_layanan.id_pasien,
                                             dt_jasa.jasa,
                                             dt_pasien_layanan.tarif,
                                             dt_pasien_layanan.jasa_dpjp as dpjp,
                                             dt_pasien_layanan.jasa_pengganti as pengganti,
                                             dt_pasien_layanan.jasa_operator as operator,
                                             dt_pasien_layanan.jasa_anastesi as anastesi,
                                             dt_pasien_layanan.jasa_pendamping as pendamping,
                                             dt_pasien_layanan.jasa_konsul as konsul,
                                             dt_pasien_layanan.jasa_laborat as laborat,
                                             dt_pasien_layanan.jasa_tanggung as tanggung,
                                             dt_pasien_layanan.jasa_radiologi as radiologi,
                                             dt_pasien_layanan.jasa_rr as rr');
                              }
                            ))
                            ->where('dt_pasien_layanan.id_dpjp',$detil->id_dpjp)
                            ->where('dt_pasien_layanan.id_pasien_jenis',$bpjs->id_pasien_jenis)
                            ->whereNotNull('dt_pasien_layanan.keluar')
                            ->whereDate('dt_pasien_layanan.keluar','>=',$bpjs->awal)
                            ->whereDate('dt_pasien_layanan.keluar','<=',$bpjs->akhir)
                            ->orderby('dt_pasien.no_mr')
                            ->orderby('dt_pasien.nama')
                            ->orderby('ruang')
                            ->paginate(10); 

          return view('mobile.bpjs_rincian',compact('rincian','jasa','user','bpjs','detil'));
        } else {
          $rincian        = DB::table('dt_pasien_layanan')
                              ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                              ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                              ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                              ->leftjoin('dt_pasien_jenis_rawat','dt_pasien_layanan.id_pasien_jenis_rawat','=','dt_pasien_jenis_rawat.id')
                              ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                              ->selectRaw('dt_pasien_layanan.id,
                                           dt_pasien_layanan.id_pasien_ruang,
                                           dt_pasien_layanan.id_pasien,
                                           dt_jasa.jasa,
                                           dt_pasien_layanan.tarif,
                                           dt_pasien_layanan.jasa_dpjp as dpjp,
                                           dt_pasien_layanan.jasa_pengganti as pengganti,
                                           dt_pasien_layanan.jasa_operator as operator,
                                           dt_pasien_layanan.jasa_anastesi as anastesi,
                                           dt_pasien_layanan.jasa_pendamping as pendamping,
                                           dt_pasien_layanan.jasa_konsul as konsul,
                                           dt_pasien_layanan.jasa_laborat as laborat,
                                           dt_pasien_layanan.jasa_tanggung as tanggung,
                                           dt_pasien_layanan.jasa_radiologi as radiologi,
                                           dt_pasien_layanan.jasa_rr as rr,
                                           (SELECT dt_ruang.ruang
                                            FROM dt_ruang
                                            WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                           (SELECT dt_ruang.ruang
                                            FROM dt_ruang
                                            WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_tindakan,
                                           dt_pasien_jenis_rawat.jenis_rawat,
                                           dt_pasien_jenis.jenis,
                                           dt_pasien.nama as pasien,
                                           dt_pasien.no_mr,
                                           CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register')
                              ->where('dt_pasien_layanan.id_dpjp',$detil->id_dpjp)
                              ->where('dt_pasien_layanan.id_pasien_jenis',$bpjs->id_pasien_jenis)
                              ->whereNotNull('dt_pasien_layanan.keluar')
                              ->whereDate('dt_pasien_layanan.keluar','>=',$bpjs->awal)
                              ->whereDate('dt_pasien_layanan.keluar','<=',$bpjs->akhir)
                              ->orderby('dt_pasien.no_mr')
                              ->orderby('dt_pasien.nama')
                              ->orderby('ruang')
                              ->get();

          return view('bpjs_rincian',compact('rincian','jasa','user','bpjs','detil'));
        }
    }

    public function bpjs_rincian_cetak($id){
        $detil    = DB::table('dt_claim_bpjs')
                      ->where('dt_claim_bpjs.id',Crypt::decrypt($id))
                      ->first();

        $bpjs     = DB::table('dt_claim_bpjs_stat')
                      ->where('id',$detil->id_stat)
                      ->selectRaw('dt_claim_bpjs_stat.awal,
                                   dt_claim_bpjs_stat.akhir,
                                   dt_claim_bpjs_stat.id_pasien_jenis,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal,"%d %M %Y") as t_awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir,"%d %M %Y") as t_akhir')
                      ->first();

        $user   = DB::table('users')
                    ->where('id',$detil->id_dpjp)
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->first();               

        $jasa           = DB::table('dt_pasien_layanan')
                            ->selectRaw('SUM(dt_pasien_layanan.tarif) as tarif,
                                         SUM(dt_pasien_layanan.jasa_dpjp) as dpjp,
                                         SUM(dt_pasien_layanan.jasa_pengganti) as pengganti,
                                         SUM(dt_pasien_layanan.jasa_operator) as operator,
                                         SUM(dt_pasien_layanan.jasa_anastesi) as anastesi,
                                         SUM(dt_pasien_layanan.jasa_pendamping) as pendamping,
                                         SUM(dt_pasien_layanan.jasa_konsul) as konsul,
                                         SUM(dt_pasien_layanan.jasa_laborat) as laborat,
                                         SUM(dt_pasien_layanan.jasa_tanggung) as tanggung,
                                         SUM(dt_pasien_layanan.jasa_radiologi) as radiologi,
                                         SUM(dt_pasien_layanan.jasa_rr) as rr,
                                         SUM(dt_pasien_layanan.jasa_dpjp) +
                                         SUM(dt_pasien_layanan.jasa_pengganti) +
                                         SUM(dt_pasien_layanan.jasa_operator) +
                                         SUM(dt_pasien_layanan.jasa_anastesi) +
                                         SUM(dt_pasien_layanan.jasa_pendamping) +
                                         SUM(dt_pasien_layanan.jasa_konsul) +
                                         SUM(dt_pasien_layanan.jasa_laborat) +
                                         SUM(dt_pasien_layanan.jasa_tanggung) +
                                         SUM(dt_pasien_layanan.jasa_radiologi) +
                                         SUM(dt_pasien_layanan.jasa_rr) as medis')

                            ->where('dt_pasien_layanan.id_dpjp',$detil->id_dpjp)
                            ->where('dt_pasien_layanan.id_pasien_jenis',$bpjs->id_pasien_jenis)
                            ->whereNotNull('dt_pasien_layanan.keluar')
                            ->whereDate('dt_pasien_layanan.keluar','>=',$bpjs->awal)
                            ->whereDate('dt_pasien_layanan.keluar','<=',$bpjs->akhir)                            
                            ->first();

          $rincian        = DB::table('dt_pasien_layanan')
                              ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                              ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                              ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                              ->leftjoin('dt_pasien_jenis_rawat','dt_pasien_layanan.id_pasien_jenis_rawat','=','dt_pasien_jenis_rawat.id')
                              ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                              ->selectRaw('dt_pasien_layanan.id,
                                           dt_pasien_layanan.id_pasien_ruang,
                                           dt_pasien_layanan.id_pasien,
                                           dt_jasa.jasa,
                                           dt_pasien_layanan.tarif,
                                           dt_pasien_layanan.jasa_dpjp as dpjp,
                                           dt_pasien_layanan.jasa_pengganti as pengganti,
                                           dt_pasien_layanan.jasa_operator as operator,
                                           dt_pasien_layanan.jasa_anastesi as anastesi,
                                           dt_pasien_layanan.jasa_pendamping as pendamping,
                                           dt_pasien_layanan.jasa_konsul as konsul,
                                           dt_pasien_layanan.jasa_laborat as laborat,
                                           dt_pasien_layanan.jasa_tanggung as tanggung,
                                           dt_pasien_layanan.jasa_radiologi as radiologi,
                                           dt_pasien_layanan.jasa_rr as rr,
                                           (SELECT dt_ruang.ruang
                                            FROM dt_ruang
                                            WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                           (SELECT dt_ruang.ruang
                                            FROM dt_ruang
                                            WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_tindakan,
                                           dt_pasien_jenis_rawat.jenis_rawat,
                                           dt_pasien_jenis.jenis,
                                           dt_pasien.nama as pasien,
                                           dt_pasien.no_mr,
                                           CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register')
                              ->where('dt_pasien_layanan.id_dpjp',$detil->id_dpjp)
                              ->where('dt_pasien_layanan.id_pasien_jenis',$bpjs->id_pasien_jenis)
                              ->whereNotNull('dt_pasien_layanan.keluar')
                              ->whereDate('dt_pasien_layanan.keluar','>=',$bpjs->awal)
                              ->whereDate('dt_pasien_layanan.keluar','<=',$bpjs->akhir)
                              ->orderby('dt_pasien.no_mr')
                              ->orderby('dt_pasien.nama')
                              ->orderby('ruang')
                              ->get();

          return view('bpjs_rincian_cetak',compact('rincian','jasa','user','bpjs','detil'));
    }

    public function bpjs_cetak($id){
      $bpjs     = DB::table('dt_claim_bpjs_stat')
                      ->where('id',Crypt::decrypt($id))
                      ->selectRaw('dt_claim_bpjs_stat.id,
                                   dt_claim_bpjs_stat.awal,
                                   dt_claim_bpjs_stat.akhir,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal,"%d %M %Y") as tgl_awal,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir,"%d %M %Y") as tgl_akhir')
                      ->first();

      $detil   = DB::table('dt_claim_bpjs')
                    ->leftjoin('users','dt_claim_bpjs.id_dpjp','=','users.id')
                    ->selectRaw('dt_claim_bpjs.id,
                                 dt_claim_bpjs.waktu,
                                 dt_claim_bpjs.dari,
                                 dt_claim_bpjs.sampai,
                                 dt_claim_bpjs.id_dpjp,
                                 
                                 dt_claim_bpjs.nominal_inap,
                                 dt_claim_bpjs.sisa_sebelum_inap,
                                 dt_claim_bpjs.jumlah_inap,
                                 dt_claim_bpjs.claim_inap,
                                 dt_claim_bpjs.sisa_inap,
                                 dt_claim_bpjs.medis_inap,
                                 (dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap as claim_medis_inap,

                                 dt_claim_bpjs.nominal_jalan,
                                 dt_claim_bpjs.sisa_sebelum_jalan,
                                 dt_claim_bpjs.jumlah_jalan,
                                 dt_claim_bpjs.claim_jalan,
                                 dt_claim_bpjs.sisa_jalan,
                                 dt_claim_bpjs.medis_jalan,
                                 (dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan as claim_medis_jalan,
                                 dt_claim_bpjs.stat,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->where('dt_claim_bpjs.id_stat',$bpjs->id)
                    ->orderby('users.nama')
                    ->get();

        $awal   = $bpjs->awal;
        $akhir  = $bpjs->akhir;

        $tag      = DB::table('dt_claim_bpjs')
                      ->selectRaw('SUM(IF(nominal_inap > 0,nominal_inap,0)) as t_inap,
                                   SUM(IF(nominal_jalan > 0,nominal_jalan,0)) as t_jalan,
                                   SUM(IF(medis_inap > 0,medis_inap,0)) as m_inap,
                                   SUM((dt_claim_bpjs.medis_inap * dt_claim_bpjs.claim_inap) / dt_claim_bpjs.nominal_inap) as cm_inap,
                                   SUM(IF(medis_jalan > 0,medis_jalan,0)) as m_jalan,
                                   SUM(IF(claim_inap > 0,claim_inap,0)) as c_inap,
                                   SUM(IF(claim_jalan > 0,claim_jalan,0)) as c_jalan,
                                   SUM((dt_claim_bpjs.medis_jalan * dt_claim_bpjs.claim_jalan) / dt_claim_bpjs.nominal_jalan) as cm_jalan')
                      ->where('dt_claim_bpjs.id_stat',$bpjs->id)
                      ->first();

      return view('bpjs_cetak',compact('bpjs','detil','awal','akhir','tag'));
    }

    public function bpjs_export($id){
      return Excel::download(new ClaimBPJS(Crypt::decrypt($id)), 'Claim BPJS.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function bpjs_inap(request $request){
      DB::table('dt_claim_bpjs')
        ->where('id',$request->id)
        ->update([
            'claim_inap' => str_replace(',','',$request->claim_inap),
        ]);

      return response()->json();
    }

    public function bpjs_jalan(request $request){
      DB::table('dt_claim_bpjs')
        ->where('id',$request->id)
        ->update([
            'claim_jalan' => str_replace(',','',$request->claim_jalan),
        ]);

      return response()->json();
    }
}
