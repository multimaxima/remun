<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\dt_ruang;
use App\dt_ruang_jasa;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class HomeController extends Controller
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

    public function pilih_propinsi(request $request) {
        if($request->ajax()) {
            $output='';
            $pdesa  = DB::table('dt_wilayah')                     
                        ->selectRaw('id, nama, no_prop')
                        ->where('no_prop','<>',NULL)
                        ->where('no_kab',NULL)
                        ->where('no_kec',NULL)
                        ->where('no_kel',NULL)
                        ->orderby('nama')
                        ->get();

            if($pdesa) {
                $output.='<option value=""></option>';
                foreach ($pdesa as $key => $desa) { 
                    $output.='<option value="'.$desa->no_prop.'">'.strtoupper($desa->nama).'</option>';
                }

                return response($output);
            }
        }      
    }

    public function pilih_kota(request $request) {
        if(!empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                            
                            ->selectRaw('id, nama, no_kab, no_prop')
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kab','<>',NULL)
                            ->where('no_kec',NULL)
                            ->where('no_kel',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    $output.='<option value=""></option>';
                    foreach ($pdesa as $key => $desa) { 
                        $output.='<option value="'.$desa->no_kab.'">'.strtoupper($desa->nama).'</option>';
                    }

                    return response($output);
                }
            }
        }
    }

    public function pilih_kecamatan(request $request)
    {
        if(!empty($request->no_kab) && !empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                                                 
                            ->selectRaw('id, nama, no_kec, no_kab, no_prop')
                            ->where('no_kab','=',$request->no_kab)
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kec','<>',NULL)
                            ->where('no_kel',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    $output.='<option value=""></option>';
                    foreach ($pdesa as $key => $desa) { 
                        $output.='<option value="'.$desa->no_kec.'">'.strtoupper($desa->nama).'</option>';
                    }

                    return response($output);
                }
            }
        }
    }

    public function pilih_desa(request $request)
    {
        if(!empty($request->no_kec) && !empty($request->no_kab) && !empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                            
                            ->selectRaw('id, nama, no_kel, no_kec, no_kab, no_prop')
                            ->where('no_kec','=',$request->no_kec)
                            ->where('no_kab','=',$request->no_kab)
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kel','<>',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    $output.='<option value=""></option>';
                    foreach ($pdesa as $key => $desa) { 
                        $output.='<option value="'.$desa->no_kel.'">'.strtoupper($desa->nama).'</option>';
                    }

                    return response($output);
                }
            }
        }
    }

    public function pilih_propinsi_edit(request $request)
    {      
        if($request->ajax()) {
            $output='';
            $pdesa  = DB::table('dt_wilayah')                     
                        ->selectRaw('id, nama, no_prop')
                        ->where('no_prop','<>',NULL)
                        ->where('no_kab',NULL)
                        ->where('no_kec',NULL)
                        ->where('no_kel',NULL)
                        ->orderby('nama')
                        ->get();

            if($pdesa) {
                $output.='<option value=""></option>';
                foreach ($pdesa as $key => $desa) {
                    if($desa->no_prop == $request->no_prop){
                        $output.='<option value="'.$desa->no_prop.'" selected>'.strtoupper($desa->nama).'</option>';
                    } else {
                        $output.='<option value="'.$desa->no_prop.'">'.strtoupper($desa->nama).'</option>';
                    }                    
                }

                return response($output);
            }
        }      
    }

    public function pilih_kota_edit(request $request)
    {
        if(!empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                            
                            ->selectRaw('id, nama, no_kab, no_prop')
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kab','<>',NULL)
                            ->where('no_kec',NULL)
                            ->where('no_kel',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    foreach ($pdesa as $key => $desa) { 
                        if($desa->no_kab == $request->no_kab && $desa->no_prop == $request->no_prop){
                            $output.='<option value="'.$desa->no_kab.'" selected>'.strtoupper($desa->nama).'</option>';
                        } else {
                            $output.='<option value="'.$desa->no_kab.'">'.strtoupper($desa->nama).'</option>';
                        }
                    }

                    return response($output);
                }
            }
        }
    }

    public function pilih_kecamatan_edit(request $request)
    {
        if(!empty($request->no_kab) && !empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                                                 
                            ->selectRaw('id, nama, no_kec, no_kab, no_prop')
                            ->where('no_kab','=',$request->no_kab)
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kec','<>',NULL)
                            ->where('no_kel',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    foreach ($pdesa as $key => $desa) { 
                        if($desa->no_kec == $request->no_kec && $desa->no_kab == $request->no_kab && $desa->no_prop == $request->no_prop){
                            $output.='<option value="'.$desa->no_kec.'" selected>'.strtoupper($desa->nama).'</option>';
                        } else {
                            $output.='<option value="'.$desa->no_kec.'">'.strtoupper($desa->nama).'</option>';
                        }                        
                    }

                    return response($output);
                }
            }
        }
    }

    public function pilih_desa_edit(request $request)
    {
        if(!empty($request->no_kec) && !empty($request->no_kab) && !empty($request->no_prop)){
            if($request->ajax()) {
                $output='';
                $pdesa  = DB::table('dt_wilayah')                            
                            ->selectRaw('id, nama, no_kel, no_kec, no_kab, no_prop')
                            ->where('no_kec','=',$request->no_kec)
                            ->where('no_kab','=',$request->no_kab)
                            ->where('no_prop','=',$request->no_prop)
                            ->where('no_kel','<>',NULL)
                            ->orderby('nama')
                            ->get();

                if($pdesa) {
                    foreach ($pdesa as $key => $desa) { 
                        if($desa->no_kel == $request->no_kel && $desa->no_kec == $request->no_kec && $desa->no_kab == $request->no_kab && $desa->no_prop == $request->no_prop){
                            $output.='<option value="'.$desa->no_kel.'" selected>'.strtoupper($desa->nama).'</option>';
                        } else {
                            $output.='<option value="'.$desa->no_kel.'">'.strtoupper($desa->nama).'</option>';
                        }                        
                    }

                    return response($output);
                }
            }
        }
    }

    public function index(){
      $pas_bln  = DB::select('SELECT 
                                DATE_FORMAT(dt_pasien.masuk,"%b %Y") as bulan,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 1 THEN 1 ELSE 0 END) as umum,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 2 THEN 1 ELSE 0 END) as jkn,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 3 THEN 1 ELSE 0 END) as jampersal,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 4 THEN 1 ELSE 0 END) as covid,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 5 THEN 1 ELSE 0 END) as spm,
                                SUM(CASE WHEN dt_pasien.id_pasien_jenis = 6 THEN 1 ELSE 0 END) as jasa_raharja  
                              FROM 
                                dt_pasien
                              WHERE 
                                dt_pasien.masuk >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                              GROUP BY 
                                YEAR(dt_pasien.masuk),
                                MONTH(dt_pasien.masuk)
                              ORDER BY 
                                YEAR(dt_pasien.masuk),
                                MONTH(dt_pasien.masuk)');

      $pasien     = DB::select('SELECT 
                                  YEAR(dt_pasien.masuk) as tahun,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 1 THEN 1 ELSE 0 END) as umum,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 2 THEN 1 ELSE 0 END) as jkn,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 3 THEN 1 ELSE 0 END) as jampersal,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 4 THEN 1 ELSE 0 END) as covid,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 5 THEN 1 ELSE 0 END) as spm,
                                  SUM(CASE WHEN dt_pasien.id_pasien_jenis = 6 THEN 1 ELSE 0 END) as jasa_raharja 
                                FROM 
                                  dt_pasien
                                WHERE 
                                  dt_pasien.hapus = 0 AND
                                  dt_pasien.masuk >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
                                GROUP BY 
                                  YEAR(dt_pasien.masuk)
                                ORDER BY 
                                  YEAR(dt_pasien.masuk)
                                LIMIT 5;');

      $c_pasien = DB::table('dt_pasien')
                    ->selectRaw('SUM(CASE WHEN dt_pasien.id_pasien_jenis = 1 THEN 1 ELSE 0 END) as umum,
                                 SUM(CASE WHEN dt_pasien.id_pasien_jenis = 2 THEN 1 ELSE 0 END) as jkn,
                                 SUM(CASE WHEN dt_pasien.id_pasien_jenis = 3 THEN 1 ELSE 0 END) as jampersal,
                                 SUM(CASE WHEN dt_pasien.id_pasien_jenis = 4 THEN 1 ELSE 0 END) as covid,
                                 SUM(CASE WHEN dt_pasien.id_pasien_jenis = 5 THEN 1 ELSE 0 END) as spm,
                                 SUM(CASE WHEN dt_pasien.id_pasien_jenis = 6 THEN 1 ELSE 0 END) as jasa_raharja')
                    ->whereYear('dt_pasien.masuk',date("Y"))
                    ->whereMonth('dt_pasien.masuk',date("m"))
                    ->first();

      $jenis      = DB::table('dt_pasien')
                      ->selectRaw('SUM(CASE WHEN dt_pasien.id_pasien_jenis_rawat = 1 THEN 1 ELSE 0 END) as rajal,
                                   SUM(CASE WHEN dt_pasien.id_pasien_jenis_rawat = 2 THEN 1 ELSE 0 END) as ranap')
                      ->whereYear('dt_pasien.masuk',date("Y"))                      
                      ->whereMonth('dt_pasien.masuk',date("m"))                      
                      ->where('dt_pasien.hapus',0)
                      ->first();

      $j_pasien   = DB::select('SELECT COUNT(dt_pasien_ruang.id) as jml, dt_ruang.ruang
                                FROM dt_ruang
                                RIGHT OUTER JOIN dt_pasien_ruang ON (dt_ruang.id = dt_pasien_ruang.id_ruang)
                                WHERE YEAR(dt_pasien_ruang.masuk) = YEAR(CURDATE())
                                AND MONTH(dt_pasien_ruang.masuk) = MONTH(CURDATE())
                                AND dt_ruang.nonpasien = 0
                                GROUP BY dt_ruang.ruang
                                ORDER BY jml DESC
                                LIMIT 5');

      $dpjp       = DB::select('SELECT COUNT(dt_pasien_layanan.id) as jml, 
                                (SELECT 
                                CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                FROM users
                                WHERE users.id = dt_pasien_layanan.id_dpjp) as nama
                                FROM dt_pasien_layanan
                                WHERE YEAR(dt_pasien_layanan.waktu) = YEAR(CURDATE())
                                AND MONTH(dt_pasien_layanan.waktu) = MONTH(CURDATE())
                                AND dt_pasien_layanan.id_dpjp IS NOT NULL
                                GROUP BY nama
                                ORDER BY COUNT(dt_pasien_layanan.id) DESC
                                LIMIT 5'); 

      $bulan      = DB::table('parameter')     
                      ->selectRaw('DATE_FORMAT(CURDATE(),"%M %Y") as bulan')
                      ->first();

      date_default_timezone_set("Asia/Jakarta");
      $jam = date('H:i');

      if ($jam > '04:30' && $jam < '10:00') {
        $salam = 'pagi';
      } elseif ($jam >= '10:00' && $jam < '14:00') {
        $salam = 'siang';
      } elseif ($jam < '18:00') {
        $salam = 'sore';
      } else {
        $salam = 'malam';
      }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.index',compact('c_pasien','jenis','j_pasien','dpjp','salam','pasien','pas_bln','bulan'));
        } else {
            return view('index',compact('c_pasien','jenis','j_pasien','dpjp','salam','pasien','pas_bln','bulan'));
            //return view('index',compact('salam','bulan'));
        }

      //dd(json_encode($pasum));
    }

    public function parameter(){
        $karyawan   = DB::table('users')        
                        ->where('users.hapus',0)
                        ->where('users.id','>',1)
                        ->orderby('users.nama')
                        ->selectRaw('users.id,
                                     CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                     IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                        ->get();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('mobile.parameter',compact('karyawan'));
        } else {
            return view('parameter',compact('karyawan'));
        }
    }

    public function parameter_simpan(request $request){
        DB::table('parameter')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'nama' => $request->nama,
                'alias' => $request->alias,
                'alamat' => $request->alamat,
                'no_prop' => $request->no_prop,
                'no_kab' => $request->no_kab,
                'no_kec' => $request->no_kec,
                'no_kel' => $request->no_kel,
                'telp' => $request->telp,
                'fax' => $request->fax,
                'email' => $request->email,
                'web' => $request->web,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'likedin' => $request->likedin,
                'google' => $request->google,
                'youtube' => $request->youtube,
                'id_ketua_tim' => $request->id_ketua_tim,
                'id_direktur' => $request->id_direktur,
                'direktur_plt' => $request->direktur_plt,
                'id_bendahara' => $request->id_bendahara,
                'id_pelaksana' => $request->id_pelaksana,
                'petugas_update' => Auth::user()->id,
            ]);

        if($request->logo){
            $this->validate($request, [
                'logo' => 'required|image|mimes:png|max:20480'
            ]);

            $image = $request->file('logo');
            $input['imagename'] = 'logo.'.$image->getClientOriginalExtension();
    
            $destinationPath = public_path('images');
            $img = Image::make($image->getRealPath());
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
        }

        if($request->web_logo){
            $this->validate($request, [
                'web_logo' => 'required|image|mimes:png|max:20480'
            ]);

            $image = $request->file('web_logo');
            $input['imagename'] = 'web_logo.'.$image->getClientOriginalExtension();
    
            $destinationPath = public_path('images');
            $img = Image::make($image->getRealPath());
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
        }

        $agent = new Agent();

        if ($agent->isMobile()) {
          Toastr::success('Data parameter berhasil disimpan.');
          return back();
        } else {
          return redirect()->back()->with('success','Data parameter berhasil disimpan.');
        }        
    }

    public function parameter_software(){
        $total  = DB::table('parameter')
                    ->selectRaw('parameter.medis_perawat + admin as total_penghasil,
                                 parameter.pos_remun + parameter.direksi + parameter.staf as total_nonpenghasil')
                    ->first();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('mobile.parameter_software',compact('total'));
        } else {
            return view('parameter_software',compact('total'));
        }
    }

    public function parameter_software_simpan(request $request){
        $koreksi  = str_replace(',','',$request->koreksi);

        DB::table('parameter')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'koreksi' => $koreksi,                
                'medis_perawat' => $request->medis_perawat,
                'admin' => $request->admin,
                'pos_remun' => $request->pos_remun,
                'direksi' => $request->direksi,
                'staf' => $request->staf,
                'farmasi' => $request->farmasi,
                'dokter_umum' => $request->dokter_umum,
                'anastesi' => $request->anastesi,
                'histori' => $request->histori,
                'dasar_remun' => $request->dasar_remun,
                'petugas_update' => Auth::user()->id,
                'pot_spesialis' => $request->pot_spesialis,
                'pot_apotik' => $request->pot_apotik,
                'pot_hd' => $request->pot_hd,
                'pot_nutrisionis' => $request->pot_nutrisionis,
            ]);

        $agent = new Agent();

        if ($agent->isMobile()) {
          Toastr::success('Parameter Remunerasi berhasil disimpan.');
          return back();
        } else {
          return redirect()->back()->with('success','Parameter Remunerasi berhasil disimpan.');
        }
    }

    public function profil(){
        $bagian   = DB::table('users_tenaga_bagian')->orderby('bagian')->get();
        $status   = DB::table('users_status')->get();
        $ruang    = DB::table('dt_ruang')->orderby('ruang')->get();
        $bank     = DB::table('dt_bank')->orderby('bank')->get();

        $karyawan   = DB::table('users')
                      ->leftjoin('users_akses','users.id_akses','=','users_akses.id')
                       ->where('users.id',Auth::user()->id)
                       ->selectRaw('IF(users.keluar IS NULL,
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
                                    (users.perform_nilai * users.perform_bobot)) as skore')
                       ->first();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('mobile.profil',compact('bagian','status','ruang','bank','karyawan'));
        } else {
            return view('profil',compact('bagian','status','ruang','bank','karyawan'));
        }        
    }

    public function profil_password_form(){
      $agent = new Agent();
      
      if ($agent->isMobile()) {
        return view('mobile.profil_password');
      } else {
        return view('profil_password');
      }
    }

    public function profil_password(request $request){
        if (!(Hash::check($request->current_password, Auth::user()->password))) {        
        return redirect()->back()->with("error","Password yang Anda masukkan salah. Silahkan ulangi kembali.");
      }

      if(strcmp($request->current_password, $request->new_password) == 0){
        return redirect()->back()->with("error","Password baru tidak boleh sama dengan password lama. Silahkan memilih password yang lain.");
      }

      if($request->new_password !== $request->new_password_confirm){
        return redirect()->back()->with("error","Konfirmasi password yang Anda masukkan salah. Silahkan ulangi kembali.");
      }

      $validatedData = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8',
      ]);

      DB::table('users')
        ->where('id',Auth::user()->id)
        ->update([
          'password' => bcrypt($request->new_password),
          'pass' => $request->new_password,
        ]);

      return redirect()->back()->with('success','Password berhasil dirubah.');
    }

    public function profil_simpan(request $request){
        DB::table('users')
            ->where('id',Auth::user()->id)
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
                'telp' => $request->telp,
                'hp' => $request->hp,
                'email' => $request->email,
                'npwp' => $request->npwp,
                'rekening' => $request->rekening,
                'bank' => $request->bank,
                'petugas_update' => Auth::user()->id,
            ]);

        if($request->foto){
            $this->validate($request, [
                'foto' => 'required|image|mimes:jpg,jpeg|max:20480'
            ]);

            $image = $request->file('foto');
            $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
    
            $destinationPath = public_path('dokumen/karyawan');
            $img = Image::make($image->getRealPath());
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);

            DB::table('users')
                ->where('id',Auth::user()->id)
                ->update([
                    'foto' => 'dokumen/karyawan/'.$input['imagename'],
                ]);
        }

        Toastr::success('Data profil berhasil disimpan.');
        return back();
    }

    public function bank(request $request){
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

        $bank   = DB::table('dt_bank')
                  ->where('dt_bank.hapus',0)
                  
                  ->when($cari, function ($query) use ($cari) {
                    return $query->where('dt_bank.bank','LIKE','%'.$cari.'%');
                  })                  

                  ->orderby('dt_bank.bank')
                  ->paginate($tampil);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
           return view('mobile.bank',compact('bank','tampil','cari'));
        } else {
          return view('bank',compact('bank','tampil','cari'));
        }        
    }

    public function bank_hapus($id){
        DB::table('dt_bank')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data bank berhasil dihapus.');
          return back();
        } else {
          return redirect()->back()->with('success','Data bank berhasil dihapus.');
        }        
    }

    public function bank_baru(request $request){
        DB::table('dt_bank')
            ->insert([
                'bank' => $request->bank,
                'cabang' => $request->cabang,
                'petugas_create' => Auth::user()->id,
                'petugas_update' => Auth::user()->id,
            ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data bank berhasil ditambahkan.');
          return back();
        } else {
          return redirect()->back()->with('success','Data bank berhasil ditambahkan.');
        }
    }

    public function bank_edit_show(request $request){
      $data   = DB::table('dt_bank')
                  ->where('id',$request->id)
                  ->selectRaw('dt_bank.id,
                               dt_bank.bank,
                               dt_bank.cabang')
                  ->first();

        echo json_encode($data);
    }

    public function bank_edit(request $request){
        DB::table('dt_bank')
            ->where('id',$request->id)
            ->update([
                'bank' => $request->bank,
                'cabang' => $request->cabang,
                'petugas_update' => Auth::user()->id,
            ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data bank berhasil disimpan.');
          return back();
        } else {
          return redirect()->back()->with('success','Data bank berhasil disimpan.');
        }
    }

    public function ruang(request $request){
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

        $ruang  = dt_ruang::selectRaw('dt_ruang.id,
                                       dt_ruang.ruang,
                                       dt_ruang.jalan,
                                       dt_ruang.inap,
                                       dt_ruang.terima_pasien,
                                       dt_ruang.nonpasien,
                                       dt_ruang.p_gawat_darurat,
                                       dt_ruang.p_resiko,
                                       dt_ruang.m_gawat_darurat,
                                       dt_ruang.m_resiko')
                          ->with(array(
                              'ruang_jasa' => function($jasa){
                                $jasa->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                                     ->selectRaw('dt_ruang_jasa.id,
                                                  dt_ruang_jasa.id_ruang,
                                                  dt_ruang_jasa.id_jasa,
                                                  dt_jasa.jasa')
                                     ->where('dt_ruang_jasa.hapus',0);
                              }
                          ))
                          ->with(array(
                              'ruang_jenis' => function($jen){
                                $jen->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')
                                     ->selectRaw('dt_ruang_jenis.id,
                                                  dt_ruang_jenis.id_ruang,
                                                  dt_ruang_jenis.id_pasien_jenis,
                                                  dt_ruang_jenis.aktif,
                                                  dt_pasien_jenis.jenis');
                              }
                          ))
                          ->where('dt_ruang.hapus',0)

                          ->when($cari, function ($query) use ($cari) {
                              return $query->where('dt_ruang.ruang','LIKE','%'.$cari.'%');
                            })                  

                          ->orderby('dt_ruang.ruang')
                          ->paginate($tampil);

        $layanan= DB::table('dt_jasa')
                    ->where('dt_jasa.hapus',0)
                    ->orderby('dt_jasa.jasa')
                    ->get();

        $jenis  = DB::table('dt_pasien_jenis')
                    ->where('dt_pasien_jenis.hapus',0)
                    ->orderby('dt_pasien_jenis.id')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.ruang',compact('ruang','layanan','jenis','tampil','cari'));
        } else {
            return view('ruang',compact('ruang','layanan','jenis','tampil','cari'));
        }
    }

    public function ruang_hapus($id){
        DB::table('dt_ruang')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data ruang berhasil dihapus.');
          return back();
        } else {
          return redirect()->back()->with('success','Data ruang berhasil dihapus.');
        }        
    }

    public function ruang_baru(request $request){
        DB::table('dt_ruang')
          ->insert([
              'ruang' => $request->ruang,
              'jalan' => $request->jalan,
              'inap' => $request->inap,
              'terima_pasien' => $request->terima_pasien,
              'nonpasien' => $request->nonpasien,
              'm_gawat_darurat' => $request->m_gawat_darurat,
              'm_resiko' => $request->m_resiko,
              'p_gawat_darurat' => $request->p_gawat_darurat,
              'p_resiko' => $request->p_resiko,
              'petugas_create' => Auth::user()->id,
              'petugas_update' => Auth::user()->id,
          ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data ruang berhasil ditambahkan.');
          return back();
        } else {
          return redirect()->back()->with('success','Data ruang berhasil ditambahkan.');
        }        
    }

    public function ruang_editing(request $request){
      $data   = DB::table('dt_ruang')
                  ->where('id',$request->id)
                  ->selectRaw('dt_ruang.id,
                               dt_ruang.ruang,
                               dt_ruang.jalan,
                               dt_ruang.inap,
                               dt_ruang.terima_pasien,
                               dt_ruang.nonpasien,
                               dt_ruang.m_gawat_darurat,
                               dt_ruang.m_resiko,
                               dt_ruang.p_gawat_darurat,
                               dt_ruang.p_resiko')
                  ->first();

        echo json_encode($data);
    }

    public function ruang_edit(request $request){
        DB::table('dt_ruang')
          ->where('id',$request->id)
          ->update([
              'ruang' => $request->ruang,
              'jalan' => $request->jalan,
              'inap' => $request->inap,
              'terima_pasien' => $request->terima_pasien,
              'nonpasien' => $request->nonpasien,
              'm_gawat_darurat' => $request->m_gawat_darurat,
              'm_resiko' => $request->m_resiko,
              'p_gawat_darurat' => $request->p_gawat_darurat,
              'p_resiko' => $request->p_resiko,
              'petugas_update' => Auth::user()->id,
          ]);

        DB::table('users')
          ->where('id_ruang',$request->id)
          ->update([
            'petugas_update' => Auth::user()->id,
          ]);

        DB::table('users_history')
          ->where('id_ruang',$request->id)
          ->update([
            'petugas_update' => Auth::user()->id,
          ]);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          Toastr::success('Data ruang berhasil disimpan.');
          return back();
        } else {
          return redirect()->back()->with('success','Data ruang berhasil disimpan.');
        }        
    }

    public function ruang_layanan($id){
        $ruang      = DB::table('dt_ruang')->where('id',Crypt::decrypt($id))->first();

        $layanan    = DB::table('dt_jasa')
                        ->where('dt_jasa.hapus',0)
                        ->orderby('dt_jasa.jasa')
                        ->get();

        $d_layanan  = DB::table('dt_ruang_jasa')
                        ->leftjoin('dt_jasa','dt_ruang_jasa.id_jasa','=','dt_jasa.id')
                        ->selectRaw('dt_ruang_jasa.id,
                                     dt_ruang_jasa.id_ruang,
                                     dt_ruang_jasa.id_jasa,
                                     dt_jasa.jasa')
                        ->where('dt_ruang_jasa.hapus',0)
                        ->where('dt_ruang_jasa.id_ruang',$ruang->id)
                        ->get();

        return view('ruang_layanan',compact('ruang','layanan','d_layanan'));
    }

    public function ruang_layanan_hapus($id){
        DB::table('dt_ruang_jasa')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data layanan berhasil dihapus.');
    }

    public function ruang_jenis(request $request){
      $cek  = DB::table('dt_ruang_jenis')
                ->where('id',$request->id)
                ->first();

      if($cek->aktif == 1){
        DB::table('dt_ruang_jenis')
          ->where('id',$request->id)
          ->update([
            'aktif' => 0
          ]);        
      } else {
        DB::table('dt_ruang_jenis')
          ->where('id',$request->id)
          ->update([
            'aktif' => 1
          ]);        
      }

      return response()->json(['return' => 'some data']);
    }

    public function ruang_layanan_baru(request $request){
      foreach($request->id_jasa as $key => $id_jasa) {
        DB::table('dt_ruang_jasa')
          ->insert([
              'id_ruang' => $request->id_ruang,
              'id_jasa' => $request->input('id_jasa')[$key],
              'petugas_create' => Auth::user()->id,
              'petugas_update' => Auth::user()->id,
          ]);
      }

        return redirect()->back()->with('success','Data layanan berhasil ditambahkan.');
    }    

    public function ruang_layanan_baru_show(request $request){
      $data   = $request->id;

      echo json_encode($data);
    }    

    public function ruang_layanan_editing(request $request){
      $data   = DB::table('dt_ruang_jasa')
                  ->where('id',$request->id)
                  ->selectRaw('dt_ruang_jasa.id,
                               dt_ruang_jasa.id_jasa')
                  ->first();

        echo json_encode($data);
    }

    public function ruang_layanan_edit(request $request){
        DB::table('dt_ruang_jasa')
            ->where('id',$request->id)
            ->update([
                'id_jasa' => $request->id_jasa,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data layanan berhasil disimpan.');
    }

    public function ruang_jenis_edit(request $request){
        DB::table('dt_ruang_jenis')
            ->where('id',$request->id)
            ->update([
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data jenis pasien berhasil disimpan.');
    }

    public function download(){
        return view('download');
    }

    public function menu($id){
        $akses  = DB::table('users_akses')
                    ->where('users_akses.id',1)
                    ->get();

        return view('menu',compact('akses'));
    }

    public function menu_simpan(request $request){
        DB::table('users_akses')
            ->where('id',$request->id)
            ->update([
                'rem_perawat' => $request->rem_perawat,
                'rem_admin' => $request->rem_admin,
                'komulatif' => $request->komulatif,
                'petugas_update' => Auth::user()->id,
            ]);

        return back();
    }

    public function informasi_software(){
      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.informasi');
      } else {
        return view('informasi');
      }
    }

    public function absensi(request $request) {
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

      $absensi  = DB::table('users_absen')
                    ->when($cari, function ($query) use ($cari) {
                      return $query->where('users_absen.absen','LIKE','%'.$cari.'%');
                    })
                    ->orderby('users_absen.absen')
                    ->paginate($tampil);

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.absensi',compact('cari','tampil','absensi'));
      } else {
        return view('absensi',compact('cari','tampil','absensi'));
      }      
    }

    public function absensi_simpan_show(request $request) {
      $data   = DB::table('users_absen')
                  ->where('id',$request->id)
                  ->first();

      echo json_encode($data);
    }

    public function absensi_simpan(request $request) {
      DB::table('users_absen')
        ->where('id',$request->id)
        ->update([
          'absen' => strtoupper($request->absen),
          'indeks' => $request->indeks,
          'staf' => $request->staf,
          'administrasi' => $request->administrasi,
          'jasa' => $request->jasa,
        ]);

      return redirect()->back()->with('success','Jenis absensi berhasil disimpan.');
    }

    public function absensi_baru(request $request) {
      DB::table('users_absen')
        ->insert([
          'absen' => strtoupper($request->absen),
          'indeks' => $request->indeks,
          'staf' => $request->staf,
          'administrasi' => $request->administrasi,
          'jasa' => $request->jasa,
        ]);

      return redirect()->back()->with('success','Jenis absensi berhasil disimpan.');
    }
}