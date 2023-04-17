<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Exports\DataKaryawan;
use App\Exports\IndeksKaryawan;
use App\Exports\JasaKaryawan;
use Excel;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class KaryawanController extends Controller
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

    public function karyawan(request $request){
      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }

      if($request->id_akses){
        $id_akses   = $request->id_akses;
      } else {
        $id_akses   = '';
      }

      if($request->tampil){
        $tampil   = $request->tampil;
      } else {
        $tampil   = 10;
      }

      if($request->cari){
        $cari   = $request->cari;
      } else {
        $cari   = '';
      }

      if($request->aktif){
        $aktif   = $request->aktif;
      } else {
        $aktif   = 0;
      }

      $ruang      = DB::table('dt_ruang')
                      ->where('dt_ruang.hapus',0)
                      ->orderby('dt_ruang.ruang')
                      ->get();

      $akses      = DB::table('users_akses')
                      ->get();

      $bagian      = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $karyawan    = DB::table('users')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_status','users.id_status','=','users_status.id')
                       ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.jabatan,
                                    users.golongan,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

				    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,
   
                                    users.rekening,
                                    users.hapus,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga_bagian.urut,
                                    users_akses.akses,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang) as ruang,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang_1) as ruang_1,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang_2) as ruang_2,
                                    users_status.status')
                       ->where('users.hapus',$aktif)
                       ->where('users.id','>',1)

                       ->when($cari, function ($query) use ($cari) {
                          return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })

                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang)
                                         ->orwhere('users.id_ruang_1',$id_ruang)
                                         ->orwhere('users.id_ruang_2',$id_ruang);
                        })

                       ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                       ->when($id_akses, function ($query) use ($id_akses) {
                            return $query->where('users.id_akses',$id_akses);
                        })

                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->paginate($tampil);

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.karyawan',compact('karyawan','ruang','id_ruang','id_bagian','bagian','tampil','cari','akses','id_akses','aktif'));
      } else {
        return view('karyawan',compact('karyawan','ruang','id_ruang','id_bagian','bagian','tampil','cari','akses','id_akses','aktif'));
      }        
    }

    public function karyawan_update_history(request $request){
      $karyawan   = DB::table('users')
                      ->where('id',$request->id)
                      ->first();

      $mulai = date('2020-01-01');

      if($karyawan->mulai_kerja < $mulai){
        $awal = $mulai;
      } else {
        $awal = $karyawan->mulai_kerja;
      }

      if($karyawan->keluar){
        $akhir  = $karyawan->keluar;
      } else {
        $akhir  = date('Y-m-d');
      }

      DB::select('CALL update_histori("'.$karyawan->id.'","'.$awal.'","'.$akhir.'");');

      return back();
    }

    public function karyawan_update_history_ulang(request $request){
      $karyawan   = DB::table('users')
                      ->where('id',$request->id)
                      ->first();

      $mulai = date('2019-01-01');

      if($karyawan->mulai_kerja < $mulai){
        $awal = $mulai;
      } else {
        $awal = $karyawan->mulai_kerja;
      }

      if($karyawan->keluar){
        $akhir  = $karyawan->keluar;
      } else {
        $akhir  = date('Y-m-d');
      }

      DB::select('CALL update_histori_ulang("'.$karyawan->id.'","'.$awal.'","'.$akhir.'");');

      return back();
    }

    public function karyawan_hapus($id){
      DB::table('users')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);

      $agent = new Agent();

      if ($agent->isMobile()) {
        Toastr::success('Data karyawan berhasil dihapus.');
        return back();
      } else {
        return redirect()->back()->with('success','Data karyawan berhasil dihapus.');
      }    	
    }

    public function karyawan_cuti_simpan(request $request){
      $cek  = DB::table('users')
                ->where('id',$request->id)
                ->first();

      if($cek->cuti == 1){
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'cuti' => 0,
            'hadir' => 0,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal',date("Y-m-d"))
          ->update([
            'cuti' => 0,
            'hadir' => 0,
            'petugas_update' => Auth::user()->id,
          ]);
      } else {
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'cuti' => 1,
            'hadir' => 1,
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal',date("Y-m-d"))
          ->update([
            'cuti' => 1,
            'hadir' => 1,
            'petugas_update' => Auth::user()->id,
          ]);
      }      

      return response()->json();
    }

    public function karyawan_reset($id){
    	DB::table('users')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'password' => bcrypt('123456'),
          'petugas_update' => Auth::user()->id,
    		]);

      $agent = new Agent();

      if ($agent->isMobile()) {
        Toastr::success('Password karyawan berhasil direset.');
        return back();
      } else {
        return redirect()->back()->with('success','Password karyawan berhasil direset.');
      }    	
    }

    public function karyawan_baru(request $request){
      $bagian   = DB::table('users_tenaga_bagian')->where('users_tenaga_bagian.hapus',0)->orderby('bagian')->get();
      $status   = DB::table('users_status')->where('users_status.hapus',0)->get();
      $ruang    = DB::table('dt_ruang')->where('dt_ruang.hapus',0)->orderby('dt_ruang.ruang')->get();
      $bank     = DB::table('dt_bank')->where('dt_bank.hapus',0)->orderby('dt_bank.bank')->get();
      $akses    = DB::table('users_akses')->get();

      $cari       = $request->cari;
      $tampil     = $request->tampil;
      $id_bagian  = $request->id_bagian;
      $id_ruang   = $request->id_ruang;

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.karyawan_baru',compact('bagian','status','ruang','bank','akses','cari','tampil','id_bagian','id_ruang'));
      } else {
        return view('karyawan_baru',compact('bagian','status','ruang','bank','akses','cari','tampil','id_bagian','id_ruang'));
      }      
    }

    public function karyawan_baru_simpan(request $request){
      $cek = DB::table('users')
                ->where('username',$request->username)
                ->get();

      if(count($cek) == 0){
        DB::table('users')
          ->insert([
            'nama' => $request->nama,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'nip' => $request->nip,            
            'alamat' => $request->alamat,
            'dusun' => $request->dusun,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'no_prop' => $request->no_prop,
            'no_kab' => $request->no_kab,
            'no_kec' => $request->no_kec,
            'no_kel' => $request->no_kel,
            'temp_lahir' => $request->temp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'id_kelamin' => $request->id_kelamin,
            'email' => $request->email,
            'telp' => $request->telp,
            'hp' => $request->hp,
            'mulai_kerja' => $request->mulai_kerja,
            'id_tenaga_bagian' => $request->id_tenaga_bagian,
            'id_status' => $request->id_status,
            'id_ruang' => $request->id_ruang,
            'id_ruang_1' => $request->id_ruang_1,
            'id_ruang_2' => $request->id_ruang_2,
            'pendidikan' => $request->pendidikan,
            'gapok' => str_replace(',','',$request->gapok),
            'koreksi' => str_replace(',','',$request->koreksi),            
            'pajak' => str_replace(',','',$request->pajak),
            'tpp' => str_replace(',','',$request->tpp),
            'golongan' => $request->golongan,
            'npwp' => $request->npwp,
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'id_akses' => $request->id_akses,
            'username' => strtolower($request->username),
            'password' => bcrypt('123456'),            
            'petugas_create' => Auth::user()->id,
            'petugas_update' => Auth::user()->id,
          ]);

        $baru   = DB::table('users')
                    ->orderby('id','desc')
                    ->first();

        if($request->foto){
          $this->validate($request, [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
          ]);

          $image = $request->file('foto');
          $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
    
          $destinationPath = public_path('dokumen/karyawan');
          $img = Image::make($image->getRealPath());
          $img->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
          })->save($destinationPath.'/'.$input['imagename']);

          DB::table('users')
            ->where('id',$baru->id)
            ->update([
              'foto' => 'dokumen/karyawan/'.$input['imagename']
            ]);
        }
       
        return redirect()->route('karyawan_edit',Crypt::encrypt($baru->id));
      } else {
        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Username telah dipergunakan oleh karyawan lain.');
          return back();
        } else {
          return redirect()->back()->with('error','Username telah dipergunakan oleh karyawan lain.')->withInput();
        }        
      }
    }

    public function karyawan_edit(request $request){
      $cari       = $request->cari;
      $tampil     = $request->tampil;
      $id_bagian  = $request->id_bagian;
      $id_ruang   = $request->id_ruang;

    	$karyawan 	= DB::table('users')
                      ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
    					         ->where('users.id',$request->id)
                       ->selectRaw('users.id,
                                    users.foto,
                                    users.nama,
                                    users.gelar_depan,
                                    users.gelar_belakang,
                                    users.nip,
                                    users.alamat,
                                    users.dusun,
                                    users.rt,
                                    users.rw,
                                    users.no_prop,
                                    users.no_kab,
                                    users.no_kec,
                                    users.no_kel,
                                    users.temp_lahir,
                                    users.tgl_lahir,
                                    users.id_kelamin,
                                    users.mulai_kerja,
                                    users.keluar,
                                    users.id_tenaga_bagian,
                                    users.telp,
                                    users.hp,
                                    users.email,
                                    users.id_status,
                                    users.id_ruang,
                                    users.id_ruang_1,
                                    users.id_ruang_2,
                                    users.pendidikan,
                                    users.gapok,
                                    users.koreksi,
                                    users.dasar_bobot,
                                    users.pend_nilai,
                                    users.pend_bobot,
                                    users.diklat_nilai,
                                    users.diklat_bobot,
                                    users.temp_tugas,
                                    users.resiko_nilai,
                                    users.resiko_bobot,
                                    users.gawat_nilai,
                                    users.gawat_bobot,
                                    users.jabatan,
                                    users.jab_nilai,
                                    users.jab_bobot,
                                    users.panitia_nilai,
                                    users.panitia_bobot,
                                    users.perform_nilai,
                                    users.perform_bobot,

                                    IF(users.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW()), " Tahun ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,NOW()) % 12, " Bulan"),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar), " Tahun ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,users.keluar) % 12, " Bulan")) as masa_kerja,

                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) as masa_kerja_nilai,
                                    users.masa_kerja_bobot,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,

                                    users.pajak,
                                    users.tpp,
                                    users.jasa_tambahan,
                                    users.jp_perawat,
                                    users.jp_admin,
                                    users.pos_remun,
                                    users.direksi,
                                    users.staf,
                                    users.apoteker,
                                    users.ass_apoteker,
                                    users.admin_farmasi,
                                    users.pen_anastesi,
                                    users.per_asisten_1,
                                    users.per_asisten_2,
                                    users.instrumen,
                                    users.sirkuler,
                                    users.per_pendamping_1,
                                    users.per_pendamping_2,
                                    users.fisioterapis,
                                    users.pemulasaran,
                                    users.medis,
                                    users.golongan,
                                    users.npwp,
                                    users.rekening,
                                    users.bank,
                                    users.tgl_hapus,
                                    users.hapus,
                                    users.id_akses,
                                    users.username,
                                    users.interensif,
                                    users_akses.akses')
    					         ->first();

      $bagian   = DB::table('users_tenaga_bagian')->where('users_tenaga_bagian.hapus',0)->orderby('bagian')->get();
      $status   = DB::table('users_status')->where('users_status.hapus',0)->get();
      $ruang    = DB::table('dt_ruang')->where('dt_ruang.hapus',0)->orderby('dt_ruang.ruang')->get();
      $bank     = DB::table('dt_bank')->where('dt_bank.hapus',0)->orderby('dt_bank.bank')->get();
      $akses    = DB::table('users_akses')->get();      

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.karyawan_edit',compact('karyawan','bagian','status','ruang','bank','akses','cari','tampil','id_bagian','id_ruang'));
      } else {
        return view('karyawan_edit',compact('karyawan','bagian','status','ruang','bank','akses','cari','tampil','id_bagian','id_ruang'));
      }
    }

    public function cuti_hapus($id){
      DB::table('users_cuti')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        Toastr::success('Data cuti karyawan berhasil dihapus.');
        return back();
      } else {
        return redirect()->back()->with('success','Data cuti karyawan berhasil dihapus.');
      }      
    }

    public function cuti_baru(request $request){
      DB::table('users_cuti')
        ->insert([
          'id_users' => Crypt::decrypt($request->id_users),
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'keterangan' => $request->keterangan,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data cuti karyawan berhasil ditambahkan.');
    }

    public function cuti_edit_show(request $request){
      $data   = DB::table('users_cuti')
                  ->where('users_cuti.id',$request->id)
                  ->selectRaw('users_cuti.id,
                               users_cuti.awal,
                               users_cuti.akhir,
                               users_cuti.keterangan')
                  ->first();

      echo json_encode($data);
    }

    public function cuti_edit(request $request){
      DB::table('users_cuti')
        ->where('id',$request->id)
        ->update([
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'keterangan' => $request->keterangan,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data cuti karyawan berhasil disimpan.');
    }

    public function karyawan_edit_simpan(request $request){
      DB::table('users')
        ->where('id',Crypt::decrypt($request->id))
        ->update([
          'nama' => $request->nama,
          'gelar_depan' => $request->gelar_depan,
          'gelar_belakang' => $request->gelar_belakang,
          'nip' => $request->nip,
          'alamat' => $request->alamat,
          'dusun' => $request->dusun,
          'rt' => $request->rt,
          'rw' => $request->rw,
          'no_prop' => $request->no_prop,
          'no_kab' => $request->no_kab,
          'no_kec' => $request->no_kec,
          'no_kel' => $request->no_kel,
          'temp_lahir' => $request->temp_lahir,
          'tgl_lahir' => $request->tgl_lahir,
          'id_kelamin' => $request->id_kelamin,
          'mulai_kerja' => $request->mulai_kerja,
          'keluar' => $request->keluar,
          'id_tenaga_bagian' => $request->id_tenaga_bagian,
          'telp' => $request->telp,
          'hp' => $request->hp,
          'email' => $request->email,
          'id_status' => $request->id_status,
          'id_ruang' => $request->id_ruang,
          'id_ruang_1' => $request->id_ruang_1,
          'id_ruang_2' => $request->id_ruang_2,
          'pendidikan' => $request->pendidikan,
          'gapok' => str_replace(',','',$request->gapok),
          'koreksi' => str_replace(',','',$request->koreksi),
          'pajak' => str_replace(',','',$request->pajak),
          'tpp' => str_replace(',','',$request->tpp),          
          'golongan' => $request->golongan,
          'npwp' => $request->npwp,
          'rekening' => $request->rekening,
          'bank' => $request->bank,
          'id_akses' => $request->id_akses,
          'temp_tugas' => $request->temp_tugas,
          'jabatan' => $request->jabatan,
          'hapus' => $request->status,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->foto){
        $this->validate($request, [
          'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ]);

        $image = $request->file('foto');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
    
        $destinationPath = public_path('dokumen/karyawan');
        $img = Image::make($image->getRealPath());
        $img->resize(800, 800, function ($constraint) {
          $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);

        DB::table('users')
          ->where('id',Crypt::decrypt($request->id))
          ->update([
            'foto' => 'dokumen/karyawan/'.$input['imagename']
          ]);
      }    

      return redirect()->route('karyawan')->with('success','Data karyawan berhasil disimpan.');
    }

    public function karyawan_jasa_indek_simpan(request $request){
      DB::table('users')
        ->where('id',Crypt::decrypt($request->id))
        ->update([          
          'dasar_bobot' => $request->dasar_bobot,
          'pend_nilai' => $request->pend_nilai,
          'pend_bobot' => $request->pend_bobot,
          'diklat_nilai' => $request->diklat_nilai,
          'diklat_bobot' => $request->diklat_bobot,
          'temp_tugas' => $request->temp_tugas,
          'resiko_bobot' => $request->resiko_bobot,
          'gawat_bobot' => $request->gawat_bobot,
          'jabatan' => $request->jabatan,
          'jab_nilai' => $request->jab_nilai,
          'jab_bobot' => $request->jab_bobot,
          'panitia_nilai' => $request->panitia_nilai,
          'panitia_bobot' => $request->panitia_bobot,
          'perform_nilai' => $request->perform_nilai,
          'perform_bobot' => $request->perform_bobot,
          'masa_kerja_bobot' => $request->masa_kerja_bobot,          
          'jp_perawat' => $request->jp_perawat,
          'jp_admin' => $request->jp_admin,
          'pos_remun' => $request->pos_remun,
          'direksi' => $request->direksi,
          'staf' => $request->staf,
          'apoteker' => $request->apoteker,
          'ass_apoteker' => $request->ass_apoteker,
          'admin_farmasi' => $request->admin_farmasi,
          'pen_anastesi' => $request->pen_anastesi,
          'per_asisten_1' => $request->per_asisten_1,
          'per_asisten_2' => $request->per_asisten_2,
          'instrumen' => $request->instrumen,
          'sirkuler' => $request->sirkuler,
          'per_pendamping_1' => $request->per_pendamping_1,
          'per_pendamping_2' => $request->per_pendamping_2,
          'fisioterapis' => $request->fisioterapis,
          'pemulasaran' => $request->pemulasaran,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('users_history')
        ->where('id_users',Crypt::decrypt($request->id))
        ->where('tanggal',date("Y-m-d"))
        ->update([
          'dasar_bobot' => $request->dasar_bobot,
          'pend_nilai' => $request->pend_nilai,
          'pend_bobot' => $request->pend_bobot,
          'diklat_nilai' => $request->diklat_nilai,
          'diklat_bobot' => $request->diklat_bobot,
          'temp_tugas' => $request->temp_tugas,
          'resiko_bobot' => $request->resiko_bobot,
          'gawat_bobot' => $request->gawat_bobot,
          'jabatan' => $request->jabatan,
          'jab_nilai' => $request->jab_nilai,
          'jab_bobot' => $request->jab_bobot,
          'panitia_nilai' => $request->panitia_nilai,
          'panitia_bobot' => $request->panitia_bobot,
          'perform_nilai' => $request->perform_nilai,
          'perform_bobot' => $request->perform_bobot,
          'masa_kerja_bobot' => $request->masa_kerja_bobot,          
          'jp_perawat' => $request->jp_perawat,
          'jp_admin' => $request->jp_admin,
          'pos_remun' => $request->pos_remun,
          'direksi' => $request->direksi,
          'staf' => $request->staf,
          'apoteker' => $request->apoteker,
          'ass_apoteker' => $request->ass_apoteker,
          'admin_farmasi' => $request->admin_farmasi,
          'pen_anastesi' => $request->pen_anastesi,
          'per_asisten_1' => $request->per_asisten_1,
          'per_asisten_2' => $request->per_asisten_2,
          'instrumen' => $request->instrumen,
          'sirkuler' => $request->sirkuler,
          'per_pendamping_1' => $request->per_pendamping_1,
          'per_pendamping_2' => $request->per_pendamping_2,
          'fisioterapis' => $request->fisioterapis,
          'pemulasaran' => $request->pemulasaran,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data karyawan berhasil disimpan.');
    }

    public function karyawan_jasa(request $request){      
      if($request->cari){
        $cari   = $request->cari;
      } else {
        $cari   = '';
      }

      if($request->status){
        $status   = $request->status;
      } else {
        $status   = 0;
      }

      if($request->tampil){
        $tampil   = $request->tampil;
      } else {
        $tampil   = 10;
      }

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }

      $bagian     = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $ruang      = DB::table('dt_ruang')
                      ->orderby('dt_ruang.ruang')
                      ->where('dt_ruang.hapus',0)
                      ->get();

      $karyawan   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                   (SELECT dt_ruang.ruang
                                    FROM dt_ruang
                                    WHERE dt_ruang.id = users.id_ruang) as ruang,

                                   (SELECT users_tenaga_bagian.bagian
                                    FROM users_tenaga_bagian
                                    WHERE users_tenaga_bagian.id = users.id_tenaga_bagian) as bagian,

                                   users.id_tenaga,

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
                                   users.per_pendamping_2,
                                   users.fisioterapis,
                                   users.pemulasaran')
                      ->where('users.hapus',$status)
                      ->where('users.id','>',1)
                      ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang)
                                         ->orwhere('users.id_ruang_1',$id_ruang)
                                         ->orwhere('users.id_ruang_2',$id_ruang);
                        })
                      ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })
                      ->when($cari, function ($query) use ($cari) {
                            return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })
                      ->orderby('users_tenaga_bagian.urut')
                      ->orderby('ruang')
                      ->orderby('bagian')
                      ->orderby('users.nama')
                      ->paginate($tampil);

      $agent = new Agent();
      if ($agent->isMobile()) {
        return view('mobile.karyawan_jasa',compact('karyawan','id_ruang','ruang','bagian','id_bagian','tampil','cari','status'));
      } else {
        return view('karyawan_jasa',compact('karyawan','id_ruang','ruang','bagian','id_bagian','tampil','cari','status'));
      }      
    }

    public function karyawan_jasa_edit($id){
      $karyawan   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
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
                      ->where('users.id',Crypt::decrypt($id))
                      ->first();

      return view('mobile.karyawan_jasa_edit',compact('karyawan'));
    }

    public function karyawan_jasa_cetak(request $request){
      if($request->id_ruang){
          $id_ruang   = $request->id_ruang;
        } else {
          $id_ruang   = '';
        }

        $karyawan   = DB::table('users')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
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
                      ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang)
                                         ->orwhere('users.id_ruang_1',$id_ruang)
                                         ->orwhere('users.id_ruang_2',$id_ruang);
                        })
                      ->orderby('users_tenaga_bagian.urut')
                      ->orderby('users.nama')
                      ->get();

      return view('karyawan_jasa_cetak',compact('karyawan','id_ruang'));
    }

    public function karyawan_jasa_simpan_show(request $request){
      $data   = DB::table('users')
                  ->where('users.id',$request->id)
                  ->selectRaw('users.id,
                               CONCAT("EDIT JASA ",IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                               IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                               users.jp_perawat,
                               users.jp_admin,
                               users.pos_remun,
                               users.direksi,
                               users.staf,
                               users.apoteker,
                               users.ass_apoteker,
                               users.admin_farmasi,
                               users.pen_anastesi,
                               users.per_asisten_1,
                               users.per_asisten_2,
                               users.instrumen,
                               users.sirkuler,
                               users.per_pendamping_1,
                               users.per_pendamping_2,
                               users.fisioterapis,
                               users.pemulasaran')
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_jasa_simpan(request $request){
      DB::table('users')
        ->where('id',$request->id)
        ->update([
          'jp_perawat' => $request->jp_perawat,
          'jp_admin' => $request->jp_admin,
          'pos_remun' => $request->pos_remun,
          'direksi' => $request->direksi,
          'staf' => $request->staf,
          'apoteker' => $request->apoteker,
          'ass_apoteker' => $request->ass_apoteker,
          'admin_farmasi' => $request->admin_farmasi,
          'pen_anastesi' => $request->pen_anastesi,
          'per_asisten_1' => $request->per_asisten_1,
          'per_asisten_2' => $request->per_asisten_2,
          'instrumen' => $request->instrumen,
          'sirkuler' => $request->sirkuler,
          'per_pendamping_1' => $request->per_pendamping_1,
          'per_pendamping_2' => $request->per_pendamping_2,
          'fisioterapis' => $request->fisioterapis,
          'pemulasaran' => $request->pemulasaran,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('users_history')
        ->where('id_users',$request->id)
        ->update([
          'jp_perawat' => $request->jp_perawat,
          'jp_admin' => $request->jp_admin,
          'pos_remun' => $request->pos_remun,
          'direksi' => $request->direksi,
          'staf' => $request->staf,
          'apoteker' => $request->apoteker,
          'ass_apoteker' => $request->ass_apoteker,
          'admin_farmasi' => $request->admin_farmasi,
          'pen_anastesi' => $request->pen_anastesi,
          'per_asisten_1' => $request->per_asisten_1,
          'per_asisten_2' => $request->per_asisten_2,
          'instrumen' => $request->instrumen,
          'sirkuler' => $request->sirkuler,
          'per_pendamping_1' => $request->per_pendamping_1,
          'per_pendamping_2' => $request->per_pendamping_2,
          'fisioterapis' => $request->fisioterapis,
          'pemulasaran' => $request->pemulasaran,
          'petugas_update' => Auth::user()->id,
        ]);
      
      return redirect()->back()->with('success','Jasa karyawan berhasil disimpan.');
    }

    public function karyawan_jasa_export(){
      return Excel::download(new JasaKaryawan, 'Jasa Karyawan.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function karyawan_jasa_history(request $request){      
      if($request->awal){
        $awal   = $request->awal;
      } else {
        $awal   = date("Y-m").'-01';
      }

      if($request->akhir){
        $akhir   = $request->akhir;
      } else {
        $akhir   = date("Y-m-d");
      }

      $karyawan   = DB::table('users')
                      ->where('users.id',$request->id)
                      ->selectRaw('users.id,
                                   users.id_ruang,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->first();

      $histori    = DB::table('users_history')     
                      ->leftjoin('users_tenaga_bagian','users_history.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('users_history.id,                                   
                                   (SELECT dt_ruang.ruang
                                    FROM dt_ruang
                                    WHERE dt_ruang.id = users_history.id_ruang) as ruang,

                                   (SELECT users_tenaga_bagian.bagian
                                    FROM users_tenaga_bagian
                                    WHERE users_tenaga_bagian.id = users_history.id_tenaga_bagian) as bagian,

                                   users_history.id_tenaga,

                                   DATE_FORMAT(users_history.tanggal,"%W, %d %M %Y") as tanggal,
                                   users_history.pos_remun,
                                   users_history.direksi,
                                   users_history.staf,
                                   users_history.jp_admin,
                                   users_history.jp_perawat,
                                   users_history.insentif_perawat,
                                   users_history.apoteker,
                                   users_history.ass_apoteker,
                                   users_history.admin_farmasi,
                                   users_history.pen_anastesi,
                                   users_history.per_asisten_1,
                                   users_history.per_asisten_2,
                                   users_history.instrumen,
                                   users_history.sirkuler,
                                   users_history.per_pendamping_1,
                                   users_history.per_pendamping_2,
                                   users_history.fisioterapis,
                                   users_history.pemulasaran')
                      ->where('users_history.tanggal','>=',$awal)
                      ->where('users_history.tanggal','<=',$akhir)
                      ->where('users_history.id_users',$karyawan->id)
                      ->orderby('users_history.tanggal')
                      ->get();

      return view('karyawan_jasa_histori',compact('karyawan','histori','awal','akhir'));
    }

    public function karyawan_jasa_history_simpan(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->where('users_history.tanggal','>=',$request->awal)
        ->where('users_history.tanggal','<=',$request->akhir)
        ->update([
          'direksi' => $request->direksi,
          'staf' => $request->staf,
          'jp_admin' => $request->jp_admin,
          'pos_remun' => $request->pos_remun,
          'jp_perawat' => $request->jp_perawat,
          'apoteker' => $request->apoteker,
          'ass_apoteker' => $request->ass_apoteker,
          'admin_farmasi' => $request->admin_farmasi,
          'pen_anastesi' => $request->pen_anastesi,
          'per_asisten_1' => $request->per_asisten_1,
          'per_asisten_2' => $request->per_asisten_2,
          'instrumen' => $request->instrumen,
          'sirkuler' => $request->sirkuler,
          'per_pendamping_1' => $request->per_pendamping_1,
          'per_pendamping_2' => $request->per_pendamping_2,
          'fisioterapis' => $request->fisioterapis,
          'pemulasaran' => $request->pemulasaran,
        ]);

      return back()->with('success','Data history jasa karyawan berhasil disimpan.');
    }

    public function karyawan_indeks(request $request){      
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

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }
      
      $bagian      = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $karyawan    = DB::table('users')  
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')      
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.pendidikan,

                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users.dasar_bobot,
                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot as skor_indek,

				    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) as masa_kerja,
                                   
                                    users.masa_kerja_bobot,

                                    (IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot) as indeks_masa_kerja,

                                    (((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) as skor_dasar,

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
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as total_indeks') 
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->when($cari, function ($query) use ($cari) {
                          return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })
                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang)
                                         ->orwhere('users.id_ruang_1',$id_ruang)
                                         ->orwhere('users.id_ruang_2',$id_ruang);

                        })
                       ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->paginate($tampil);

      $ruang    = DB::table('dt_ruang')
                    ->where('dt_ruang.hapus',0)
                    ->orderby('dt_ruang.ruang')
                    ->get();

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.karyawan_indeks',compact('karyawan','id_ruang','ruang','id_bagian','bagian','tampil','cari'));
      } else {
        return view('karyawan_indeks',compact('karyawan','id_ruang','ruang','id_bagian','bagian','tampil','cari'));
      }
    }

    public function karyawan_indeks_edit($id){
      $karyawan    = DB::table('users')  
                        ->where('users.id',Crypt::decrypt($id))
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.pendidikan,

                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users.dasar_bobot,
                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot as skor_indek,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) as masa_kerja,
                                    
                                    users.masa_kerja_bobot,

                                    (IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot) as indeks_masa_kerja,

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
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as total_indeks') 
                       ->first();
        
      return view('mobile.karyawan_indeks_edit',compact('karyawan')); 
    }



    public function karyawan_indeks_export(){
      return Excel::download(new IndeksKaryawan, 'Indeks Data Karyawan.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function karyawan_indeks_cetak(request $request){
      if($request->id_ruang){
        $id_ruang = $request->id_ruang;
      } else {
        $id_ruang = '';
      }

      $karyawan    = DB::table('users')  
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')      
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.pendidikan,
                                    
                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users.dasar_bobot,
                                    ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot as skor_indek,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) as masa_kerja,

                                    users.masa_kerja_bobot,

                                    ((IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365)))) * users.masa_kerja_bobot) as indeks_masa_kerja,

                                    ((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 
                                    ((IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365)))) * users.masa_kerja_bobot)) / 2 as skor_dasar,

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
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as total_indeks') 
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->get();

      return view('karyawan_indeks_cetak',compact('karyawan'));
    }

    public function karyawan_indeks_simpan_show(request $request){
      $data   = DB::table('users')
                  ->where('id',$request->id)
                  ->selectRaw('users.id,
                               CONCAT("EDIT INDEKS ",IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                               ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                               IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as masa_kerja,
                               users.dasar_bobot,
                               users.pend_nilai,
                               users.pend_bobot,
                               users.diklat_nilai,
                               users.diklat_bobot,
                               users.resiko_nilai,
                               users.resiko_bobot,
                               users.gawat_nilai,
                               users.gawat_bobot,
                               users.jab_nilai,
                               users.jab_bobot,
                               users.panitia_nilai,
                               users.panitia_bobot,
                               users.perform_nilai,
                               users.perform_bobot,
                               users.masa_kerja_bobot')
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_indeks_simpan(request $request){
      DB::table('users')
        ->where('id',$request->id)
        ->update([
          'dasar_bobot' => $request->dasar_bobot,
          'pend_nilai' => $request->pend_nilai,
          'pend_bobot' => $request->pend_bobot,
          'diklat_nilai' => $request->diklat_nilai,
          'diklat_bobot' => $request->diklat_bobot,
          'resiko_bobot' => $request->resiko_bobot,
          'gawat_bobot' => $request->gawat_bobot,
          'jab_nilai' => $request->jab_nilai,
          'jab_bobot' => $request->jab_bobot,
          'panitia_nilai' => $request->panitia_nilai,
          'panitia_bobot' => $request->panitia_bobot,
          'perform_nilai' => $request->perform_nilai,
          'perform_bobot' => $request->perform_bobot,
          'masa_kerja_bobot' => $request->masa_kerja_bobot,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data indeks karyawan berhasil disimpan.');
    }

    public function karyawan_indeks_history(request $request){
      if($request->awal){
        $awal   = $request->awal;
      } else {
        $awal   = date("Y-m").'-01';
      }

      if($request->akhir){
        $akhir   = $request->akhir;
      } else {
        $akhir   = date("Y-m-d");
      }      

      $karyawan   = DB::table('users')
                      ->where('users.id',$request->id)
                      ->selectRaw('users.id,
                                   users.id_ruang,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->first();

      $histori    = DB::table('users_history')
                      ->leftjoin('dt_ruang','users_history.id_ruang','=','dt_ruang.id')
                      ->leftjoin('users','users_history.id_users','=','users.id')
                      ->where('users_history.id_users',$request->id)
                      ->whereDate('users_history.tanggal','>=',$awal)
                      ->whereDate('users_history.tanggal','<=',$akhir)
                      ->selectRaw('users_history.id,
                                   DATE_FORMAT(users_history.tanggal,"%W, %d %M %Y") as tanggal,
                                   dt_ruang.ruang,
                                   users_history.id_users,
                                   users_history.gapok,
                                   users_history.koreksi,
                                   users_history.pendidikan,

                                    ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users_history.dasar_bobot,
                                    ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot as skor_indek,

                                    IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as masa_kerja,
                                    
                                    users_history.masa_kerja_bobot,

                                    (IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) * users_history.masa_kerja_bobot) as indeks_masa_kerja,

                                    (((ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot) + 

                                    (IF(users_history.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) * users_history.masa_kerja_bobot)) / 2) as skor_dasar,

                                    users_history.pend_nilai,
                                    users_history.pend_bobot,
                                    (users_history.pend_nilai * users_history.pend_bobot) AS skor_pend,
                                    users_history.diklat_nilai,
                                    users_history.diklat_bobot,
                                    (users_history.diklat_nilai * users_history.diklat_bobot) AS skor_diklat,
                                    (users_history.pend_nilai * users_history.pend_bobot) + (users_history.diklat_nilai * users_history.diklat_bobot) AS indeks_komp,
                                    users_history.temp_tugas,
                                    users_history.resiko_nilai,
                                    users_history.resiko_bobot,
                                    (users_history.resiko_nilai * users_history.resiko_bobot) AS indeks_resiko,
                                    users_history.gawat_nilai,
                                    users_history.gawat_bobot,
                                    (users_history.gawat_nilai * users_history.gawat_bobot) AS indeks_kegawat,
                                    users_history.jabatan,
                                    users_history.jab_nilai,
                                    users_history.jab_bobot,
                                    (users_history.jab_nilai * users_history.jab_bobot) AS skor_jab,
                                    users_history.panitia_nilai,
                                    users_history.panitia_bobot,
                                    (users_history.panitia_nilai * users_history.panitia_bobot) AS skor_pan,
                                    (users_history.jab_nilai * users_history.jab_bobot) + (users_history.panitia_nilai * users_history.panitia_bobot) AS indeks_jabatan,
                                    users_history.perform_nilai,
                                    users_history.perform_bobot,
                                    (users_history.perform_nilai * users_history.perform_bobot) AS indeks_perform,                                    

                                    ((((ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot) + 

                                    (IF(users_history.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) * users_history.masa_kerja_bobot)) / 2) +

                                    (users_history.pend_nilai * users_history.pend_bobot) + 
                                    (users_history.diklat_nilai * users_history.diklat_bobot) + 
                                    (users_history.resiko_nilai * users_history.resiko_bobot) + 
                                    (users_history.gawat_nilai * users_history.gawat_bobot) + 
                                    (users_history.jab_nilai * users_history.jab_bobot) + 
                                    (users_history.panitia_nilai * users_history.panitia_bobot) + 
                                    (users_history.perform_nilai * users_history.perform_bobot)) as total_indeks')
                      ->orderby('users_history.tanggal')
                      ->get();

      return view('karyawan_indeks_edit',compact('awal','akhir','karyawan','histori'));
    }

    public function karyawan_indeks_edit_show(request $request){
      $data   = DB::table('users')
                  ->where('id',$request->id)
                  ->selectRaw('users.id,
                               CONCAT("EDIT INDEKS ",IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                               ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                               IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as masa_kerja,
                               users.dasar_bobot,
                               users.pend_nilai,
                               users.pend_bobot,
                               users.diklat_nilai,
                               users.diklat_bobot,
                               users.resiko_nilai,
                               users.resiko_bobot,
                               users.gawat_nilai,
                               users.gawat_bobot,
                               users.jab_nilai,
                               users.jab_bobot,
                               users.panitia_nilai,
                               users.panitia_bobot,
                               users.perform_nilai,
                               users.perform_bobot,
                               users.masa_kerja_bobot')
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_indeks_edit_simpan(request $request){
      DB::table('users_history')
        ->where('id_users',$request->id)
        ->where('tanggal','>=',$request->awal)
        ->where('tanggal','<=',$request->akhir)
        ->update([
          'dasar_bobot' => $request->dasar_bobot,
          'masa_kerja_bobot' => $request->masa_kerja_bobot,
          'pend_nilai' => $request->pend_nilai,
          'pend_bobot' => $request->pend_bobot,
          'diklat_nilai' => $request->diklat_nilai,
          'diklat_bobot' => $request->diklat_bobot,
          'resiko_bobot' => $request->resiko_bobot,
          'gawat_bobot' => $request->gawat_bobot,
          'jab_nilai' => $request->jab_nilai,
          'jab_bobot' => $request->jab_bobot,
          'panitia_nilai' => $request->panitia_nilai,
          'panitia_bobot' => $request->panitia_bobot,
          'perform_nilai' => $request->perform_nilai,
          'perform_bobot' => $request->perform_bobot,
        ]);

      return back()->with('success','Data history indeks karyawan berhasil disimpan.');
    }

    public function karyawan_cetak(request $request){
      if($request->id_ruang){
          $id_ruang   = $request->id_ruang;
        } else {
          $id_ruang   = '';
        }

        if($request->cari){
          $cari   = $request->cari;
        } else {
          $cari   = '';
        }

      $karyawan    = DB::table('users')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                       ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                       ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
                       ->leftjoin('users_status','users.id_status','=','users_status.id')
                       ->selectRaw('users.id,
                                    users.foto,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.nip,
                                    users.username,
                                    users.gapok,
                                    users.golongan,
                                    users.rekening,
                                    users_status.status,

                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) as indeks_kerja,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                      AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,

                                    users.pajak,
                                    users.tpp,
                                    users.npwp,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga.tenaga,
                                    DATE_FORMAT(users.mulai_kerja,"%d %M %Y") as mulai_kerja,
                                    IF(users.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW()), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,NOW()) % 12, " Bln."),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,users.keluar) % 12, " Bln.")) AS masa_kerja,
                                    dt_ruang.ruang,
                                    users_akses.akses')
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->when($cari, function ($query) use ($cari) {
                            return $query->where('users.nama','like','%'.$cari.'%');
                        })
                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang)
                                         ->orwhere('users.id_ruang_1',$id_ruang)
                                         ->orwhere('users.id_ruang_2',$id_ruang);
                        })
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->get();

      return view('karyawan_cetak',compact('karyawan','id_ruang'));
    }

    public function karyawan_export(){
      return Excel::download(new DataKaryawan, 'Data Karyawan.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function karyawan_gapok(request $request){            
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

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }
      
      $bagian      = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $ruang  = DB::table('dt_ruang')
                  ->where('dt_ruang.hapus',0)
                  ->orderby('dt_ruang.ruang')
                  ->get();

      $karyawan    = DB::table('users')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gapok,

                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(NOW(), users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(NOW(), users.mulai_kerja)/365)),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= (DATEDIFF(users.keluar, users.mulai_kerja)/365)
                                     AND dt_indek.sampai >= (DATEDIFF(users.keluar, users.mulai_kerja)/365))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,

                                    users.pajak,
                                    users.tpp,
                                    users.jasa_tambahan,
                                    users.koreksi,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga.tenaga')
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->when($id_ruang, function ($query) use ($id_ruang) {
                          return $query->where('users.id_ruang',$id_ruang)
                                       ->orwhere('users.id_ruang_1',$id_ruang)
                                       ->orwhere('users.id_ruang_2',$id_ruang);
                        })
                       ->when($id_bagian, function ($query) use ($id_bagian) {
                          return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })
                       ->when($cari, function ($query) use ($cari) {
                          return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })
                       ->orderby('users_tenaga_bagian.urut')
                       ->paginate($tampil);

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.karyawan_gapok',compact('karyawan','id_ruang','ruang','id_bagian','bagian','tampil','cari'));
      } else {
        return view('karyawan_gapok',compact('karyawan','id_ruang','ruang','id_bagian','bagian','tampil','cari'));
      }
    }

    public function karyawan_gapok_edit($id){
      $karyawan    = DB::table('users')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gapok,

                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    users.pajak,
                                    users.tpp,
                                    users.koreksi,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga.tenaga')
                       ->where('users.id',Crypt::decrypt($id))
                       ->first();

      return view('mobile.karyawan_gapok_edit',compact('karyawan'));
    }

    public function karyawan_gapok_simpan_show(request $request){
      $data   = DB::table('users')
                  ->where('id',$request->id)
                  ->selectRaw('users.id,
                               CONCAT("EDIT GAPOK ",IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                               IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                               users.gapok,
                               users.tpp,
                               users.koreksi,
                               users.pajak')
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_gapok_simpan(request $request){
      DB::table('users')
        ->where('id',$request->id)
        ->update([
          'gapok' => str_replace(',','',$request->gapok),
          'tpp' => str_replace(',','',$request->tpp),
          'koreksi' => str_replace(',','',$request->koreksi),
          'pajak' => $request->pajak,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data gaji pokok karyawan berhasil disimpan.');
    }

    public function karyawan_cuti(request $request){
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

      $karyawan   = DB::table('users')
                      ->where('users.hapus',0)
                      ->where('users.id','>',1)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();

      $cuti   = DB::table('users_cuti')
                  ->leftjoin('users','users_cuti.id_karyawan','=','users.id')
                  ->leftjoin('users_absen','users_cuti.id_jenis','=','users_absen.id')
                  ->selectRaw('users_cuti.id,
                               users_cuti.id_karyawan,
                               CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                               IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                               users_cuti.awal,
                               users_cuti.akhir,
                               users_cuti.id_jenis,
                               users_absen.absen,
                               DATE_FORMAT(users_cuti.awal, "%W, %d %M %Y") as tgl_awal,
                               DATE_FORMAT(users_cuti.akhir, "%W, %d %M %Y") as tgl_akhir,
                               users_cuti.keterangan')
                  ->where('users.hapus',0)
                  ->orderby('users_cuti.id','desc')
                  ->paginate($tampil);

      $jenis  = DB::table('users_absen')
                  ->where('users_absen.absen','LIKE','%Cuti%')
                  ->get();

        return view('karyawan_cuti',compact('cuti','karyawan','cari','tampil','jenis'));
    }

    public function karyawan_cuti_hapus($id){
      DB::table('users_cuti')
        ->where('id',Crypt::decrypt($id))
        ->delete();

      return redirect()->back()->with('success','Data cuti karyawan berhasil dihapus.');
    }

    public function karyawan_cuti_baru(request $request){
      DB::table('users_cuti')
        ->insert([
          'id_karyawan' => $request->id_karyawan,
          'id_jenis' => $request->id_jenis,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'keterangan' => $request->keterangan,
          'petugas_create' => Auth::user()->id,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data cuti karyawan berhasil ditambahkan.');
    }

    public function karyawan_cuti_edit_show(request $request){
      $data   = DB::table('users_cuti')
                  ->where('id',$request->id)                  
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_cuti_edit(request $request){
      DB::table('users_cuti')
        ->where('id',$request->id)
        ->update([
          'id_karyawan' => $request->id_karyawan,
          'id_jenis' => $request->id_jenis,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'keterangan' => $request->keterangan,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data cuti karyawan berhasil disimpan.');
    }

    public function karyawan_backup(request $request){
      if($request->tanggal){
        $tanggal  = $request->tanggal;
      } else {
        $tanggal  = date("Y-m-d");
      }

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

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

      $ruang      = DB::table('dt_ruang')
                      ->where('dt_ruang.hapus',0)
                      ->orderby('dt_ruang.ruang')
                      ->get();

      $karyawan    = DB::table('users_history')
                       ->leftjoin('users','users_history.id_users','=','users.id')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                       ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
                       ->leftjoin('users_status','users.id_status','=','users_status.id')
                       ->selectRaw('users.id,
                                    users.foto,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.nip,
                                    users.username,
                                    users.jabatan,
                                    users.golongan,
                                    users.interensif,
                                    users.id_tenaga_bagian,

                                    DATE_FORMAT(users_history.mulai_kerja,"%d %b %Y") as mulai_kerja,
                                    DATE_FORMAT(users_history.keluar,"%d %b %Y") as keluar,

                                    IF(users_history.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,NOW()), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users_history.mulai_kerja,NOW()) % 12, " Bln."),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,users_history.keluar), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users_history.mulai_kerja,users_history.keluar) % 12, " Bln.")) as masa_kerja,

                                    users_history.gapok,

                                    ROUND((users_history.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,users_history.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,users_history.keluar))) as indeks_kerja,

                                    ((((ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot) + 

                                    (IF(users_history.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,NOW())
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,NOW())),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,users_history.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users_history.mulai_kerja,users_history.keluar))) * users_history.masa_kerja_bobot)) / 2) +

                                    (users_history.pend_nilai * users_history.pend_bobot) + 
                                    (users_history.diklat_nilai * users_history.diklat_bobot) + 
                                    (users_history.resiko_nilai * users_history.resiko_bobot) + 
                                    (users_history.gawat_nilai * users_history.gawat_bobot) + 
                                    (users_history.jab_nilai * users_history.jab_bobot) + 
                                    (users_history.panitia_nilai * users_history.panitia_bobot) + 
                                    (users_history.perform_nilai * users_history.perform_bobot)) as skore,
   
                                    users_history.pajak,
                                    users_history.cuti,
                                    users_history.tpp,
                                    users.npwp,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga_bagian.urut,
                                    users_tenaga.tenaga,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang) as ruang,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang_1) as ruang_1,
                                    users_status.status,
                                    users_akses.akses')
                       ->where('users.hapus',0)
                       ->where('users.id','>',1)
                       ->where('users_history.tanggal',$tanggal)

                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                        })

                       ->when($cari, function ($query) use ($cari) {
                            return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })

                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->paginate($tampil);

      return view('karyawan_backup',compact('karyawan','ruang','id_ruang','tampil','cari','tanggal'));
    }

    public function rumusan_indeks(){
      $indeks   = DB::table('dt_indek')
                    ->where('dt_indek.hapus',0)
                    ->orderby('dt_indek.dari')
                    ->get();

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.indeks',compact('indeks'));
      } else {
        return view('indeks',compact('indeks'));
      }      
    }

    public function rumusan_indeks_simpan_show(request $request){
      $data   = DB::table('dt_indek')
                  ->where('id',$request->id)
                  ->selectRaw('dt_indek.id,
                               dt_indek.dari,
                               dt_indek.sampai,
                               dt_indek.indeks')
                  ->first();

      echo json_encode($data);
    }

    public function rumusan_indeks_simpan(request $request){
      DB::table('dt_indek')
        ->where('id',$request->id)
        ->update([
          'dari' => $request->dari,
          'sampai' => $request->sampai,
          'indeks' => $request->indeks,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Rumusan indeks karyawan berhasil disimpan.');
    }

    public function karyawan_histori(request $request){
      if($request->awal){
        $awal   = $request->awal;
      } else {
        $awal   = date("Y-m").'-01';
      }

      if($request->akhir){
        $akhir   = $request->akhir;
      } else {
        $akhir   = date("Y-m-d");
      }      

      $karyawan   = DB::table('users')
                      ->where('users.id',$request->id)
                      ->selectRaw('users.id,
                                   users.id_ruang,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->first();

      $histori    = DB::table('users_history')
                      ->leftjoin('users','users_history.id_users','=','users.id')
                      ->leftjoin('users_absen','users_history.hadir','=','users_absen.id')
                      ->where('users_history.id_users',$request->id)
                      ->whereDate('users_history.tanggal','>=',$awal)
                      ->whereDate('users_history.tanggal','<=',$akhir)
                      ->selectRaw('users_history.id,
                                   DATE_FORMAT(users_history.tanggal,"%W, %d %M %Y") as tanggal,

                                   users_history.id_users,
                                   users_history.id_ruang,
                                   users_history.id_ruang_1,
                                   users_history.id_ruang_2,                                   
                                   users_history.skore,
                                   users_history.hadir,
                                   IF(users_absen.absen LIKE "%CUTI%", 1,0) as cuti')
                      ->orderby('users_history.tanggal')
                      ->get();

      $ruang      = DB::table('dt_ruang')
                      ->where('dt_ruang.hapus',0)
                      ->orderby('dt_ruang.ruang')
                      ->get();

      $absen      = DB::table('users_absen')->get();

      $cuti       = DB::table('users_absen')
                      ->where('users_absen.absen','LIKE','%CUTI%')
                      ->get();

      return view('karyawan_histori',compact('karyawan','histori','awal','akhir','ruang','absen','cuti'));
    }    

    public function karyawan_histori_cuti(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->update([
          'cuti' => $request->cuti,
          'hadir' => $request->cuti,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->akhir == date("Y-m-d")){
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'cuti' => $request->cuti,
            'hadir' => $request->cuti,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return redirect()->back()->with('success','Data cuti karyawan berhasil disimpan.');
    }

    public function karyawan_histori_absen(request $request){
        DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->where('users_history.cuti',0)
        ->update([
          'hadir' => $request->hadir,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->akhir == date("Y-m-d")){
        DB::table('users')
          ->where('id',$request->id)
          ->where('cuti',0)
          ->update([
            'hadir' => $request->hadir,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return redirect()->back()->with('success','Data absensi karyawan berhasil disimpan.');
    }

    public function karyawan_histori_pindah(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->update([
          'id_ruang' => $request->id_ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->akhir == date("Y-m-d")){
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'id_ruang' => $request->id_ruang,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return redirect()->back()->with('success','Data ruang karyawan berhasil disimpan.');
    }

    public function karyawan_histori_tambahan_1(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->update([
          'id_ruang_1' => $request->id_ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->akhir == date("Y-m-d")){
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'id_ruang_1' => $request->id_ruang,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return redirect()->back()->with('success','Data ruang karyawan berhasil disimpan.');
    }

    public function karyawan_histori_tambahan_2(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->update([
          'id_ruang_2' => $request->id_ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      if($request->akhir == date("Y-m-d")){
        DB::table('users')
          ->where('id',$request->id)
          ->update([
            'id_ruang_2' => $request->id_ruang,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return redirect()->back()->with('success','Data ruang karyawan berhasil disimpan.');
    }

    public function karyawan_ruang(request $request){
      $karyawan    = DB::table('users_history')
                       ->leftjoin('users','users_history.id_users','=','users.id')
                       ->leftjoin('users_absen','users_history.hadir','=','users_absen.id')
                       ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->selectRaw('users_history.id,
                                    users_history.id_users,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,

                                    users_history.hadir,
                                    IF(users_absen.absen LIKE "%CUTI%",1,0) as cuti,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga_bagian.urut,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang) as ruang,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang_1) as ruang_1,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users.id_ruang_2) as ruang_2')
                       ->where('users_history.id_ruang',Auth::user()->id_ruang)
                       ->where('users_history.tanggal',date("Y-m-d"))                       
                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->get();

      $ruang  = DB::table('dt_ruang')
                  ->selectRaw('dt_ruang.id,
                               dt_ruang.ruang')
                  ->orderby('dt_ruang.ruang')
                  ->where('dt_ruang.id','<>',Auth::user()->id_ruang)
                  ->get();

      $tanggal  = DB::table('parameter')
                    ->selectRaw('DATE_FORMAT(CURDATE(), "%W, %d %M %Y") as tanggal')
                    ->first();

      $absen    = DB::table('users_absen')
                    ->orderby('users_absen.absen')
                    ->get();

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.karyawan_ruang',compact('karyawan','ruang','tanggal','absen'));
      } else {
        return view('karyawan_ruang',compact('karyawan','ruang','tanggal','absen'));
      }        
    }

    public function karyawan_hadir(request $request){
      DB::table('users_history')
          ->where('id',$request->id)
          ->update([
            'hadir' => $request->hadir,
            'petugas_update' => Auth::user()->id,
          ]);

      return response()->json();
    }

    public function karyawan_pindah_show(request $request){
      $data   = DB::table('users_history')
                  ->leftjoin('users','users_history.id_users','=','users.id')
                  ->where('users_history.id',$request->id)
                  ->selectRaw('users_history.id,
                               users_history.id_ruang,
                               CONCAT("Pindahkan ",IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                               IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))," ke Ruang") as nama')
                  ->first();

      echo json_encode($data);
    }

    public function karyawan_pindah_ruang(request $request){
      $cek    = DB::table('users_history')
                  ->where('id',$request->id)
                  ->first();

      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'id_ruang' => $request->id_ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('users')
        ->where('id',$cek->id_users)
        ->update([
          'id_ruang' => $request->id_ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Karyawan berhasil dipindah ruang.');
    }

    public function karyawan_absensi(request $request){
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

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }

      if($request->tanggal){
        $tanggal   = $request->tanggal;
      } else {
        $tanggal   = date('Y-m-d');
      }

      $ruang      = DB::table('dt_ruang')
                      ->where('dt_ruang.hapus',0)
                      ->orderby('dt_ruang.ruang')
                      ->get();

      $bagian      = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $absen       = DB::table('users_absen')->get();

      $karyawan    = DB::table('users_history')
                       ->leftjoin('users','users_history.id_users','=','users.id')
                       ->leftjoin('users_absen','users_history.hadir','=','users_absen.id')
                       ->leftjoin('users_tenaga_bagian','users_history.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->selectRaw('users_history.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,

                                    users_history.id_users,
                                    users_history.tanggal,
                                    users_history.hadir,
                                    IF(users_absen.absen LIKE "%CUTI%", 1,0) as cuti,
                                    users_tenaga_bagian.bagian,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang) as ruang,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang_1) as ruang_1,
                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang_2) as ruang_2')
                       ->whereDate('users_history.tanggal',$tanggal)

                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users_history.id_ruang',$id_ruang);
                        })

                       ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users_history.id_tenaga_bagian',$id_bagian);
                        })

                       ->when($cari, function ($query) use ($cari) {
                            return $query->where('users.nama',$cari);
                        })

                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users_tenaga_bagian.id_tenaga')
                       ->orderby('users.nama')
                       ->paginate($tampil);

      return view('karyawan_absensi',compact('karyawan','ruang','id_ruang','id_bagian','bagian','tanggal','tampil','cari','absen'));
    }

    public function karyawan_absen_cuti(request $request){
      if($request->cuti == 0)
      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'cuti' => $request->cuti,
          'hadir' => 0,
          'petugas_update' => Auth::user()->id,
        ]);

        if($cek->tanggal == date("Y-m-d")){
          DB::table('users')
            ->where('id',$cek->id_users)
            ->update([
              'cuti' => 0,
              'hadir' => 0,
              'petugas_update' => Auth::user()->id,
            ]);
        }

      return response()->json();
    }

    public function karyawan_absen_hadir(request $request){
      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      if($cek->hadir == 1){
        DB::table('users_history')
          ->where('id',$request->id)
          ->update([
            'hadir' => 0,
            'petugas_update' => Auth::user()->id,
          ]);

        if($cek->tanggal == date("Y-m-d")){
          DB::table('users')
            ->where('id',$cek->id_users)
            ->update([
              'hadir' => 0,
              'petugas_update' => Auth::user()->id,
            ]);
        }
      } else {
        DB::table('users_history')
          ->where('id',$request->id)
          ->update([
            'hadir' => 1,
            'petugas_update' => Auth::user()->id,
          ]);

        if($cek->tanggal == date("Y-m-d")){
          DB::table('users')
            ->where('id',$cek->id_users)
            ->update([
              'hadir' => 1,
              'petugas_update' => Auth::user()->id,
            ]);
        }
      }     

      return response()->json();
    }    

    public function karyawan_histori_admin(request $request){
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

      if($request->id_ruang){
        $id_ruang   = $request->id_ruang;
      } else {
        $id_ruang   = '';
      }

      if($request->id_bagian){
        $id_bagian   = $request->id_bagian;
      } else {
        $id_bagian   = '';
      }

      if($request->tanggal){
        $tanggal   = $request->tanggal;
      } else {
        $tanggal   = date("Y-m-d");
      }

      $ruang      = DB::table('dt_ruang')
                      ->where('dt_ruang.hapus',0)
                      ->orderby('dt_ruang.ruang')
                      ->get();

      $bagian      = DB::table('users_tenaga_bagian')
                      ->where('users_tenaga_bagian.hapus',0)
                      ->orderby('users_tenaga_bagian.urut')
                      ->get();

      $karyawan    = DB::table('users_history')
                       ->leftjoin('users','users_history.id_users','=','users.id')
                       ->leftjoin('users_absen','users_history.hadir','=','users_absen.id')
                       ->leftjoin('users_tenaga_bagian','users_history.id_tenaga_bagian','=','users_tenaga_bagian.id')
                       ->leftjoin('users_status','users_history.id_status','=','users_status.id')
                       ->selectRaw('users_history.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.golongan,
                                    users_history.pendidikan,
                                    users_history.gapok,
                                    users_history.koreksi,
                                    ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                    users_history.dasar_bobot,
                                    ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot as skor_indek,

                                    IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar))) as masa_kerja,

                                    (IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)))  * users_history.masa_kerja_bobot) as indeks_masa_kerja,

                                    (((ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot) + 

                                    (IF(users_history.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,"'.$tanggal.'")),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar))) * users_history.masa_kerja_bobot)) / 2) as skor_dasar,

                                    users_history.pend_nilai,
                                    users_history.pend_bobot,
                                    (users_history.pend_nilai * users_history.pend_bobot) AS skor_pend,
                                    users_history.diklat_nilai,
                                    users_history.diklat_bobot,
                                    (users_history.diklat_nilai * users_history.diklat_bobot) AS skor_diklat,
                                    (users_history.pend_nilai * users_history.pend_bobot) + (users_history.diklat_nilai * users_history.diklat_bobot) AS indeks_komp,
                                    users_history.temp_tugas,
                                    users_history.resiko_nilai,
                                    users_history.resiko_bobot,
                                    (users_history.resiko_nilai * users_history.resiko_bobot) AS indeks_resiko,
                                    users_history.gawat_nilai,
                                    users_history.gawat_bobot,
                                    (users_history.gawat_nilai * users_history.gawat_bobot) AS indeks_kegawat,
                                    users_history.jabatan,
                                    users_history.jab_nilai,
                                    users_history.jab_bobot,
                                    (users_history.jab_nilai * users_history.jab_bobot) AS skor_jab,
                                    users_history.panitia_nilai,
                                    users_history.panitia_bobot,
                                    (users_history.panitia_nilai * users_history.panitia_bobot) AS skor_pan,
                                    (users_history.jab_nilai * users_history.jab_bobot) + (users_history.panitia_nilai * users_history.panitia_bobot) AS indeks_jabatan,
                                    users_history.perform_nilai,
                                    users_history.perform_bobot,
                                    (users_history.perform_nilai * users_history.perform_bobot) AS indeks_perform,
                                    users_history.masa_kerja_bobot,

                                    users_history.skore,
                                    users_history.pajak,
                                    users_history.tpp,
                                    users_history.jp_perawat,
                                    users_history.jp_admin,
                                    users_history.pos_remun,
                                    users_history.direksi,
                                    users_history.staf,
                                    users_history.insentif_perawat,
                                    users_history.apoteker,
                                    users_history.ass_apoteker,
                                    users_history.admin_farmasi,
                                    users_history.pen_anastesi,
                                    users_history.per_asisten_1,
                                    users_history.per_asisten_2,
                                    users_history.instrumen,
                                    users_history.sirkuler,
                                    users_history.per_pendamping_1,
                                    users_history.per_pendamping_2,
                                    users_history.fisioterapis,
                                    users_history.pemulasaran,
                                    users_history.medis,
                                    users_history.hapus,
                                    users_history.id_akses,
                                    users_history.interensif,
                                    users_history.hadir,
                                    IF(users_absen.absen LIKE "%CUTI%",1,0) as cuti,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga_bagian.urut,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang) as ruang,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang_1) as ruang_1,

                                    (SELECT dt_ruang.ruang
                                     FROM dt_ruang
                                     WHERE dt_ruang.id = users_history.id_ruang_2) as ruang_2,

                                    users_status.status')
                       ->whereDate('users_history.tanggal',$tanggal)
                       
                       ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users_history.id_tenaga_bagian',$id_bagian);
                        })

                       ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users_history.id_ruang',$id_ruang);
                        })

                       ->when($cari, function ($query) use ($cari) {
                            return $query->where('users.nama','LIKE','%'.$cari.'%');
                        })

                       ->orderby('users_tenaga_bagian.urut')
                       ->orderby('users.nama')
                       ->paginate($tampil);

      return view('karyawan_histori_admin',compact('karyawan','ruang','id_ruang','id_bagian','bagian','tanggal','tampil','cari'));
    }

    public function karyawan_histori_update(request $request){
      if($request->awal){
        $awal   = $request->awal;
      } else {
        $awal   = date("Y-m-").'01';
      }

      if($request->akhir){
        $akhir   = $request->akhir;
      } else {
        $akhir   = date("Y-m-d");
      }

      $histori  = DB::table('users_history')
                    ->where('users_history.id_users',Auth::user()->id)
                    ->selectRaw('users_history.id,
                                 DATE_FORMAT(users_history.tanggal,"%W, %d %M %Y") as tanggal,
                                 users_history.id_users,
                                 users_history.id_ruang,
                                 users_history.id_ruang_1,
                                 users_history.id_ruang_2,
                                 users_history.skore,
                                 users_history.hadir')
                    ->whereDate('users_history.tanggal','>=',$awal)
                    ->whereDate('users_history.tanggal','<=',$akhir)
                    ->get();

      $ruang  = DB::table('dt_ruang')
                  ->where('hapus',0)
                  ->orderby('ruang')
                  ->get();

      $absen  = DB::table('users_absen')->get();

      return view('karyawan_histori_update',compact('histori','ruang','awal','akhir','absen'));
    }

    public function karyawan_histori_data(request $request){
      if($request->awal){
        $awal   = $request->awal;
      } else {
        $awal   = date("Y-m-").'01';
      }

      if($request->akhir){
        $akhir   = $request->akhir;
      } else {
        $akhir   = date("Y-m-d");
      }

      $histori  = DB::table('users_history')
                    ->where('users_history.id_users',Auth::user()->id)
                    ->selectRaw('users_history.id,
                                 DATE_FORMAT(users_history.tanggal,"%W, %d %M %Y") as tanggal,
                                 users_history.id_users,
                                 users_history.id_ruang,
                                 users_history.id_ruang_1,
                                 users_history.id_ruang_2,
                                 users_history.skore,
                                 users_history.hadir')
                    ->whereDate('users_history.tanggal','>=',$awal)
                    ->whereDate('users_history.tanggal','<=',$akhir)
                    ->get();

      $ruang  = DB::table('dt_ruang')
                  ->where('hapus',0)
                  ->orderby('ruang')
                  ->get();

      $absen  = DB::table('users_absen')->get();

      return view('karyawan_histori_data',compact('histori','ruang','awal','akhir','absen'));
    }

    public function karyawan_his_cuti(request $request){
      if($request->cuti == 0){
        $hadir = 2;
      } else {
        $hadir = 1;
      }

      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'cuti' => $request->cuti,
          'hadir' => $hadir,
          'petugas_update' => Auth::user()->id,
        ]);

      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      if($cek->tanggal == date("Y-m-d")){
        DB::table('users')
          ->where('id',$cek->id_users)
          ->update([
            'cuti' => $request->cuti,
            'hadir' => $hadir,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return response()->json();
    }

    public function karyawan_his_absen(request $request){
      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'hadir' => $request->hadir,
          'petugas_update' => Auth::user()->id,
        ]);
      
      return response()->json();
    }

    public function karyawan_his_ruang(request $request){
      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'id_ruang' => $request->ruang,
          'petugas_update' => Auth::user()->id,
        ]);

      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      if($cek->tanggal == date("Y-m-d")){
        DB::table('users')
          ->where('id',$cek->id_users)
          ->update([
            'id_ruang' => $request->ruang,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return response()->json();
    }

    public function karyawan_his_ruang_1(request $request){
      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'id_ruang_1' => $request->ruang_1,
          'petugas_update' => Auth::user()->id,
        ]);

      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      if($cek->tanggal == date("Y-m-d")){
        DB::table('users')
          ->where('id',$cek->id_users)
          ->update([
            'id_ruang_1' => $request->ruang_1,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return response()->json();
    }

    public function karyawan_his_ruang_2(request $request){
      DB::table('users_history')
        ->where('id',$request->id)
        ->update([
          'id_ruang_2' => $request->ruang_2,
          'petugas_update' => Auth::user()->id,
        ]);

      $cek  = DB::table('users_history')
                ->where('id',$request->id)
                ->first();

      if($cek->tanggal == date("Y-m-d")){
        DB::table('users')
          ->where('id',$cek->id_users)
          ->update([
            'id_ruang_2' => $request->ruang_2,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return response()->json();
    }

    public function karyawan_his_ruang_periode(request $request){
      if($request->akhir >= $request->awal){
        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal','>=',$request->awal)
          ->whereDate('tanggal','<=',$request->akhir)
          ->update([
            'id_ruang' => $request->id_ruang,
            'petugas_update' => Auth::user()->id,
          ]);

        if($request->akhir == date("Y-m-d")){
          DB::table('users')
            ->where('id',$request->id)
            ->update([
              'id_ruang' => $request->id_ruang,
              'petugas_update' => Auth::user()->id,
            ]);
        }

        return back();
      } else {
        return redirect()->back()->with('error','PERHATIAN !!! Tanggal awal harus lebih kecil dari tanggal akhir.');
      }
    }

    public function karyawan_his_ruang_1_periode(request $request){
      if($request->akhir >= $request->awal){
        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal','>=',$request->awal)
          ->whereDate('tanggal','<=',$request->akhir)
          ->update([
            'id_ruang_1' => $request->id_ruang_1,
            'petugas_update' => Auth::user()->id,
          ]);

        if($request->akhir == date("Y-m-d")){
          DB::table('users')
            ->where('id',$request->id)
            ->update([
              'id_ruang_1' => $request->id_ruang_1,
              'petugas_update' => Auth::user()->id,
            ]);
        }

        return back();
      } else {
        return redirect()->back()->with('error','PERHATIAN !!! Tanggal awal harus lebih kecil dari tanggal akhir.');
      }
    }

    public function karyawan_his_ruang_2_periode(request $request){
      if($request->akhir >= $request->awal){
        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal','>=',$request->awal)
          ->whereDate('tanggal','<=',$request->akhir)
          ->update([
            'id_ruang_2' => $request->id_ruang_2,
            'petugas_update' => Auth::user()->id,
          ]);

        if($request->akhir == date("Y-m-d")){
          DB::table('users')
            ->where('id',$request->id)
            ->update([
              'id_ruang_2' => $request->id_ruang_2,
              'petugas_update' => Auth::user()->id,
            ]);
        }

        return back();
      } else {
        return redirect()->back()->with('error','PERHATIAN !!! Tanggal awal harus lebih kecil dari tanggal akhir.');
      }
    }

    public function karyawan_his_cuti_periode(request $request){
      if($request->akhir >= $request->awal){
        DB::table('users_cuti')          
          ->insert([
            'id_karyawan' => $request->id_karyawan,
            'awal' => $request->awal,
            'akhir' => $request->akhir,
            'keterangan' => $request->keterangan,
            'id_jenis' => $request->id_jenis,
            'petugas_update' => Auth::user()->id,
          ]);

        return back();
      } else {
        return redirect()->back()->with('error','PERHATIAN !!! Tanggal awal harus lebih kecil dari tanggal akhir.');
      }
    }

    public function karyawan_his_hadir_periode(request $request){
      if($request->akhir >= $request->awal){
        DB::table('users_history')
          ->where('id_users',$request->id)
          ->whereDate('tanggal','>=',$request->awal)
          ->whereDate('tanggal','<=',$request->akhir)
          ->update([
            'hadir' => $request->hadir,
            'petugas_update' => Auth::user()->id,
          ]);

        if($request->akhir == date("Y-m-d")){
          DB::table('users')
            ->where('id',$request->id)
            ->update([
              'hadir' => $request->hadir,
              'petugas_update' => Auth::user()->id,
            ]);
        }

        return back();
      } else {
        return redirect()->back()->with('error','PERHATIAN !!! Tanggal awal harus lebih kecil dari tanggal akhir.');
      }
    }

    public function karyawan_histori_all(request $request){
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

      $karyawan    = DB::table('users')
                       ->selectRaw('users.id,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.jabatan,
                                    users.golongan')
                       ->where('users.id','>',1)
                       ->orderby('users.nama')
                       ->get();      

      if($request->id_karyawan){
        $id_karyawan = $request->id_karyawan;

        $histori  = DB::table('users_history')
                      ->leftjoin('users','users_history.id_users','=','users.id')
                      ->selectRaw('users_history.id,
                                   DATE_FORMAT(users_history.tanggal,"%d %M %Y") as tanggal,
                                   users_history.id_tenaga_bagian,
                                   users_history.id_tenaga,
                                   users_history.id_status,
                                   users_history.id_ruang,
                                   users_history.id_ruang_1,
                                   users_history.id_ruang_2,
                                   users_history.pendidikan,
                                   users_history.gapok,
                                   users_history.koreksi,

                                   ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) AS indeks_dasar,
                                   users_history.dasar_bobot,



                                   ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot as skor_indek,



                                   IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)),

                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar))) as masa_kerja,

                                  (IF(users_history.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar))) * users_history.masa_kerja_bobot) as indeks_masa_kerja,

                                    (((ROUND((users_history.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users_history.dasar_bobot) + 

                                    (IF(users_history.keluar IS NULL,
                                     
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.tanggal)),

                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users_history.keluar))) * users_history.masa_kerja_bobot)) / 2) as skor_dasar,

                                   users_history.pend_nilai,
                                   users_history.pend_bobot,
                                   (users_history.pend_nilai * users_history.pend_bobot) AS skor_pend,
                                   users_history.diklat_nilai,
                                   users_history.diklat_bobot,
                                   (users_history.diklat_nilai * users_history.diklat_bobot) AS skor_diklat,
                                   (users_history.pend_nilai * users_history.pend_bobot) + (users_history.diklat_nilai * users_history.diklat_bobot) AS indeks_komp,
                                   
                                   users_history.temp_tugas,

                                   users_history.resiko_nilai,
                                   users_history.resiko_bobot,
                                   (users_history.resiko_nilai * users_history.resiko_bobot) AS indeks_resiko,

                                   users_history.gawat_nilai,
                                   users_history.gawat_bobot,
                                   (users_history.gawat_nilai * users_history.gawat_bobot) AS indeks_kegawat,

                                   users_history.jabatan,
                                   users_history.jab_nilai,
                                   users_history.jab_bobot,
                                   (users_history.jab_nilai * users_history.jab_bobot) AS skor_jab,

                                   users_history.panitia_nilai,
                                   users_history.panitia_bobot,
                                   (users_history.panitia_nilai * users_history.panitia_bobot) AS skor_pan,

                                   (users_history.jab_nilai * users_history.jab_bobot) + (users_history.panitia_nilai * users_history.panitia_bobot) AS indeks_jabatan,

                                   users_history.perform_nilai,
                                   users_history.perform_bobot,
                                   (users_history.perform_nilai * users_history.perform_bobot) AS indeks_perform,

                                   users_history.masa_kerja_bobot,
                                   users_history.skore,
                                   users_history.pajak,
                                   users_history.tpp,
                                   users_history.jp_perawat,
                                   users_history.jp_admin,
                                   users_history.pos_remun,
                                   users_history.direksi,
                                   users_history.staf,
                                   users_history.insentif_perawat,
                                   users_history.apoteker,
                                   users_history.ass_apoteker,
                                   users_history.admin_farmasi,
                                   users_history.pen_anastesi,
                                   users_history.per_asisten_1,
                                   users_history.per_asisten_2,
                                   users_history.instrumen,
                                   users_history.sirkuler,
                                   users_history.per_pendamping_1,
                                   users_history.per_pendamping_2,
                                   users_history.fisioterapis,
                                   users_history.pemulasaran,
                                   users_history.medis,
                                   users_history.hapus,
                                   users_history.id_akses,
                                   users_history.interensif,
                                   users_history.hadir')
                      ->where('users_history.id_users',$request->id_karyawan)
                      ->whereDate('users_history.tanggal','>=',$request->awal)
                      ->whereDate('users_history.tanggal','<=',$request->akhir)
                      ->get();

        $ruang        = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->orderby('ruang')
                        ->get();

        $bagian       = DB::table('users_tenaga_bagian')
                          ->where('users_tenaga_bagian.hapus',0)
                          ->orderby('users_tenaga_bagian.bagian')
                          ->get();

        $status       = DB::table('users_status')
                          ->where('hapus',0)
                          ->get();
      } else {
        $id_karyawan = null;
        $histori = null;
        $ruang = null;
        $bagian = null;
        $status = null;
      }

      return view('karyawan_histori_all',compact('karyawan','awal','akhir','id_karyawan','histori','ruang','bagian','status'));
    }

    public function karyawan_histori_all_update(request $request){
      DB::table('users_history')
        ->where('id',$request->id)
        ->update($request->except('_token'));

      return back();
    }

    public function karyawan_histori_all_kolektif(request $request){
      DB::table('users_history')
        ->where('users_history.id_users',$request->id_users)
        ->whereDate('users_history.tanggal','>=',$request->awal)
        ->whereDate('users_history.tanggal','<=',$request->akhir)
        ->update($request->except(['_token','id_users','awal','akhir']));

      return back();
    }
}
