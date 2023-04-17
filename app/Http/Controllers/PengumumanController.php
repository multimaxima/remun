<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Crypt;
use Auth;

class PengumumanController extends Controller
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
    public function pengumuman() {
      $umum = DB::table('pengumuman')
                ->leftjoin('users','pengumuman.id_karyawan','=','users.id')
                ->selectRaw('pengumuman.id,
                             CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                             IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                             pengumuman.judul,
                             DATE_FORMAT(pengumuman.created_at,"%d %M %Y - %H:%i") as created_at,
                             DATE_FORMAT(pengumuman.awal,"%d %M %Y") as awal,
                             DATE_FORMAT(pengumuman.akhir,"%d %M %Y") as akhir')
                ->orderby('pengumuman.id','desc')
                ->get();      

      return view('pengumuman',compact('umum'));
    }    

    public function pengumuman_hapus($id) {
      DB::table('pengumuman')
        ->where('id',Crypt::decrypt($id))
        ->delete();

      return back();
    }

    public function pengumuman_baru() {
      $karyawan   = DB::table('users')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->where('users.id','>',1)
                      ->get();

      return view('pengumuman_baru',compact('karyawan'));
    }

    public function pengumuman_baru_simpan(request $request) {
      DB::table('pengumuman')
        ->insert([
          'id_karyawan' => $request->id_karyawan,
          'judul' => $request->judul,
          'isi' => $request->isi,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
        ]);

      return redirect()->route('pengumuman')->with('success','Pengumuman berhasil ditambahkan.');
    }

    public function pengumuman_edit($id) {
      $umum   = DB::table('pengumuman')
                  ->where('id',Crypt::decrypt($id))
                  ->first();

      $karyawan   = DB::table('users')
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->where('users.id','>',1)
                      ->get();

      return view('pengumuman_edit',compact('umum','karyawan'));
    }

    public function pengumuman_edit_simpan(request $request) {
      DB::table('pengumuman')
        ->where('id',$request->id)
        ->update([
          'id_karyawan' => $request->id_karyawan,
          'judul' => $request->judul,
          'isi' => $request->isi,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
        ]);

      return redirect()->route('pengumuman')->with('success','Pengumuman berhasil disimpan.');
    }

    public function pengumuman_user() {
      $umum = DB::table('pengumuman')
                ->leftjoin('users','pengumuman.id_karyawan','=','users.id')
                ->selectRaw('pengumuman.id,
                             CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                             IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                             pengumuman.judul,
                             DATE_FORMAT(pengumuman.created_at,"%d %M %Y - %H:%i") as created_at,
                             DATE_FORMAT(pengumuman.awal,"%d %M %Y") as awal,
                             DATE_FORMAT(pengumuman.akhir,"%d %M %Y") as akhir')
                ->orderby('pengumuman.id','desc')
                ->whereDate('pengumuman.awal','<=',date("Y-m-d"))
                ->whereDate('pengumuman.akhir','>=',date("Y-m-d"))
                ->get();      

      return view('pengumuman_user',compact('umum'));
    }

    public function pengumuman_detil($id) {
      $umum = DB::table('pengumuman')
                ->leftjoin('users','pengumuman.id_karyawan','=','users.id')                
                ->selectRaw('pengumuman.id,
                             CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                             IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                             pengumuman.judul,
                             pengumuman.isi,
                             DATE_FORMAT(pengumuman.created_at,"%d %M %Y - %H:%i") as created_at,
                             DATE_FORMAT(pengumuman.awal,"%d %M %Y") as awal,
                             DATE_FORMAT(pengumuman.akhir,"%d %M %Y") as akhir')
                ->orderby('pengumuman.id','desc')
                ->where('pengumuman.id',Crypt::decrypt($id))
                ->first();      

      return view('pengumuman_detil',compact('umum'));
    }
}
