<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\dt_perhitungan;
use App\dt_perhitungan_1;
use App\dt_perhitungan_2;
use App\dt_perhitungan_3;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class LayananController extends Controller
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

    public function jasa_layanan(request $request){
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

    	$jasa 	= DB::table('dt_jasa')
    				      ->where('dt_jasa.hapus',0)
                  ->when($cari, function ($query) use ($cari) {
                    return $query->where('dt_jasa.jasa','LIKE','%'.$cari.'%');
                  })
                  ->orderby('jenis')
                  ->orderby('jasa')
    				      ->paginate($tampil);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.layanan_jasa',compact('jasa','tampil','cari'));
        } else {
    	   return view('layanan_jasa',compact('jasa','tampil','cari'));
        }
    }

    public function jasa_layanan_hapus($id){
    	DB::table('dt_jasa')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'hapus' => 1,
          'petugas_update' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data layanan berhasil dihapus.');
    }

    public function jasa_layanan_edit_show(request $request){
      $data   = DB::table('dt_jasa')
    		          ->where('id',$request->id)
    		          ->selectRaw('dt_jasa.id,
                               dt_jasa.jenis,
                               dt_jasa.operasi,
                               dt_jasa.jasa')
                  ->first();

    	echo json_encode($data);
    }

    public function jasa_layanan_edit(request $request){
      DB::table('dt_jasa')
        ->where('id',$request->id)
        ->update([
          'jenis' => $request->jenis,
          'jasa' => $request->jasa,
          'operasi' => $request->operasi,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data layanan berhasil disimpan.');
    }

    public function jasa_layanan_baru(request $request){
    	DB::table('dt_jasa')
    		->insert([
    			'jenis' => $request->jenis,
    			'jasa' => $request->jasa,
          'operasi' => $request->operasi,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data layanan berhasil ditambahkan.');
    }

    public function kategori_layanan(request $request){
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

    	$kategori  = DB::table('dt_kategori_jasa')
    					       ->where('dt_kategori_jasa.hapus',0)
                     ->when($cari, function ($query) use ($cari) {
                        return $query->where('dt_kategori_jasa.kategori','LIKE','%'.$cari.'%');
                      })
                     ->orderby('dt_kategori_jasa.kategori')
    					       ->paginate($tampil);

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.layanan_kategori',compact('kategori','tampil','cari'));
      } else {
        return view('layanan_kategori',compact('kategori','tampil','cari'));
      }
    }

    public function kategori_layanan_hapus($id){
    	DB::table('dt_kategori_jasa')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'hapus' => 1,
          'petugas_update' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data kategori layanan berhasil dihapus.');
    }

    public function kategori_layanan_baru(request $request){
    	DB::table('dt_kategori_jasa')
    		->insert([
    			'kategori' => $request->kategori,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data kategori layanan berhasil ditambahkan.');
    }

    public function kategori_layanan_edit_show(request $request){
      $data   = DB::table('dt_kategori_jasa')
    		          ->where('id',$request->id)
                  ->selectRaw('dt_kategori_jasa.id,
                               dt_kategori_jasa.kategori')
                  ->first();

    	echo json_encode($data);
    }

    public function kategori_layanan_edit(request $request){
      DB::table('dt_kategori_jasa')
        ->where('id',$request->id)
        ->update([
          'kategori' => $request->kategori,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Data kategori layanan berhasil disimpan.');
    }

    public function rekening_layanan(request $request){
      if($request->tampil){
        $tampil = $request->tampil;
      } else {
        $tampil = 10;
      }

      if($request->cari){
        $cari = $request->cari;
      } else {
        $cari = '';
      }

    	$rekening    = DB::table('dt_rekening_perhitungan')
    					         ->where('hapus',0)
                       ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_rekening_perhitungan.nama','LIKE','%'.$cari.'%');
                        })
                       ->orderby('dt_rekening_perhitungan.level')
                       ->orderby('dt_rekening_perhitungan.nama')
    					         ->paginate($tampil);

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.layanan_rekening',compact('rekening','tampil','cari'));
        } else {
            return view('layanan_rekening',compact('rekening','tampil','cari'));
        }    	
    }

    public function rekening_layanan_hapus($id){
    	DB::table('dt_rekening_perhitungan')
    		->where('id',Crypt::decrypt($id))
    		->update([
    			'hapus' => 1,
          'petugas_update' => Auth::user()->id,
    		]);

      return redirect()->back()->with('success','Data rekening layanan berhasil dihapus.');
    }

    public function rekening_layanan_baru(request $request){
    	DB::table('dt_rekening_perhitungan')
    		->insert([
    			'nama' => $request->nama,
          'level' => $request->level,
    			'individu' => $request->individu,
    			'kelompok' => $request->kelompok,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data rekening layanan berhasil ditambahkan.');
    }

    public function rekening_layanan_edit(request $request){
    	DB::table('dt_rekening_perhitungan')
    		->where('id',$request->id)
    		->update([
    			'nama' => $request->nama,
          'level' => $request->level,
    			'individu' => $request->individu,
    			'kelompok' => $request->kelompok,
          'petugas_update' => Auth::user()->id,
    		]);

    	return redirect()->back()->with('success','Data rekening layanan berhasil disimpan.');
    }

    public function rekening_layanan_edit_show(request $request){
      $data   = DB::table('dt_rekening_perhitungan')
                  ->where('id',$request->id)
                  ->selectRaw('dt_rekening_perhitungan.id,
                              dt_rekening_perhitungan.nama,
                              dt_rekening_perhitungan.level,
                              dt_rekening_perhitungan.individu,
                              dt_rekening_perhitungan.kelompok')
                  ->first();

      echo json_encode($data);
    }

    public function bagian(request $request){
      if($request->tampil){
        $tampil = $request->tampil;
      } else {
        $tampil = 10;
      }

      if($request->cari){
        $cari = $request->cari;
      } else {
        $cari = '';
      }

        $tenaga     = DB::table('users_tenaga')->where('hapus',0)->get();

        $bagian     = DB::table('users_tenaga_bagian')
                        ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                        ->leftjoin('dt_rekening_perhitungan','users_tenaga_bagian.id_rekening','=','dt_rekening_perhitungan.id')
                        ->selectRaw('users_tenaga_bagian.id,
                                     users_tenaga_bagian.id_tenaga,
                                     users_tenaga_bagian.bagian,
                                     users_tenaga_bagian.pos_remun,
                                     users_tenaga_bagian.insentif_perawat,
                                     users_tenaga_bagian.direksi,
                                     users_tenaga_bagian.administrasi,
                                     users_tenaga_bagian.medis,
                                     users_tenaga_bagian.langsung_ruangan,
                                     users_tenaga_bagian.id_rekening,
                                     users_tenaga_bagian.kel_perawat,
                                     users_tenaga_bagian.urut,
                                     users_tenaga.tenaga,
                                     dt_rekening_perhitungan.nama')
                        ->where('users_tenaga_bagian.hapus',0)
                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('users_tenaga_bagian.bagian','LIKE','%'.$cari.'%');
                        })
                        ->orderby('users_tenaga_bagian.urut')
                        ->paginate($tampil);                        

        $rekening   = DB::table('dt_rekening_perhitungan')
                        ->orderby('nama')
                        ->where('hapus',0)
                        ->where('level',3)
                        ->get();
        
        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.bagian',compact('tenaga','bagian','rekening','tampil','cari'));
        } else {
            return view('bagian',compact('tenaga','bagian','rekening','tampil','cari'));
        }        
    }

    public function bagian_tenaga(request $request){
      if($request->tampil){
        $tampil = $request->tampil;
      } else {
        $tampil = 10;
      }

      if($request->cari){
        $cari = $request->cari;
      } else {
        $cari = '';
      }

        $tenaga     = DB::table('users_tenaga')
                        ->where('hapus',0)
                        ->when($cari, function ($query) use ($cari) {
                            return $query->where('users_tenaga.tenaga','LIKE','%'.$cari.'%');
                        })
                        ->paginate($tampil);                        

        $bagian     = DB::table('users_tenaga_bagian')
                        ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                        ->leftjoin('dt_rekening_perhitungan','users_tenaga_bagian.id_rekening','=','dt_rekening_perhitungan.id')
                        ->selectRaw('users_tenaga_bagian.id,
                                     users_tenaga_bagian.id_tenaga,
                                     users_tenaga_bagian.bagian,
                                     users_tenaga_bagian.pos_remun,
                                     users_tenaga_bagian.insentif_perawat,
                                     users_tenaga_bagian.direksi,
                                     users_tenaga_bagian.administrasi,
                                     users_tenaga_bagian.medis,
                                     users_tenaga_bagian.langsung_ruangan,
                                     users_tenaga_bagian.id_rekening,
                                     users_tenaga_bagian.urut,
                                     users_tenaga.tenaga,
                                     dt_rekening_perhitungan.nama')
                        ->where('users_tenaga_bagian.hapus',0)                        
                        ->get();

        $rekening   = DB::table('dt_rekening_perhitungan')
                        ->orderby('nama')
                        ->where('hapus',0)
                        ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.bagian_tenaga',compact('tenaga','bagian','rekening','tampil','cari'));
        } else {
            return view('bagian_tenaga',compact('tenaga','bagian','rekening','tampil','cari'));
        }        
    }

    public function bagian_tenaga_hapus($id){
        DB::table('users_tenaga')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tenaga berhasil dihapus.');
    }

    public function bagian_tenaga_edit_show(request $request){
      $data   = DB::table('users_tenaga')
                  ->where('id',$request->id)
                  ->selectRaw('users_tenaga.id,
                               users_tenaga.tenaga')
                  ->first();

        echo json_encode($data);
    }

    public function bagian_tenaga_edit(request $request){
        DB::table('users_tenaga')
            ->where('id',$request->id)
            ->update([
                'tenaga' => $request->tenaga,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tenaga berhasil disimpan.');
    }

    public function bagian_tenaga_baru(request $request){
        DB::table('users_tenaga')
            ->insert([
                'tenaga' => $request->tenaga,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tenaga baru berhasil ditambahkan.');
    }

    public function bagian_hapus($id){
        DB::table('users_tenaga_bagian')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data bagian berhasil dihapus.');
    }

    public function bagian_baru(request $request){
        DB::table('users_tenaga_bagian')
            ->insert([
                'id_tenaga' => $request->id_tenaga,
                'bagian' => $request->bagian,
                'insentif_perawat' => $request->insentif_perawat,
                'direksi' => $request->direksi,
                'administrasi' => $request->administrasi,
                'id_rekening' => $request->id_rekening,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data bagian berhasil ditambahkan.');
    }

    public function bagian_edit_show(request $request){
      $data   = DB::table('users_tenaga_bagian')
                  ->where('id',$request->id)
                  ->selectRaw('users_tenaga_bagian.id,
                               users_tenaga_bagian.id_tenaga,
                               users_tenaga_bagian.bagian,
                               users_tenaga_bagian.insentif_perawat,
                               users_tenaga_bagian.direksi,
                               users_tenaga_bagian.administrasi,
                               users_tenaga_bagian.id_rekening')
                  ->first();

        echo json_encode($data);
    }

    public function bagian_edit(request $request){
        DB::table('users_tenaga_bagian')
            ->where('id',$request->id)
            ->update([
                'id_tenaga' => $request->id_tenaga,
                'bagian' => $request->bagian,
                'insentif_perawat' => $request->insentif_perawat,
                'direksi' => $request->direksi,
                'administrasi' => $request->administrasi,
                'id_rekening' => $request->id_rekening,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data bagian berhasil disimpan.');
    }

    public function tarif_daftar(request $request){
        $jenis    = DB::table('dt_pasien_jenis')
                      ->where('hapus',0)
                      ->get();

        $rawat    = DB::table('dt_pasien_jenis_rawat')->get();        

        if($request->has('jns') && $request->has('rwt')){
            $jns    = $request->jns;
            $rwt    = $request->rwt;

            $perhitungan    = dt_perhitungan::leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_perhitungan.id,
                                             dt_perhitungan.id_pasien_jenis,
                                             dt_perhitungan.id_kategori_jasa,
                                             dt_perhitungan.id_jasa,
                                             dt_kategori_jasa.kategori,
                                             dt_jasa.jasa,
                                             (SELECT SUM(dt_perhitungan_1.nilai)
                                              FROM dt_perhitungan_1
                                              WHERE dt_perhitungan_1.id_perhitungan = dt_perhitungan.id) as nilai_sub')
                                ->where('dt_perhitungan.id_pasien_jenis',$jns)
                                ->where('dt_perhitungan.id_pasien_jenis_rawat',$rwt)
                                ->where('dt_perhitungan.hapus',0)
                                ->orderby('dt_jasa.jasa')
                                ->with(array(
                                    'perhitungan_1' => function($perhitungan_1){
                                        $perhitungan_1->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                        ->selectRaw('dt_perhitungan_1.id,
                                                     dt_perhitungan_1.id_perhitungan,
                                                     dt_perhitungan_1.id_rekening,
                                                     dt_perhitungan_1.nilai,
                                                     dt_rekening_perhitungan.nama,
                                                     (SELECT SUM(dt_perhitungan_2.nilai)
                                                      FROM dt_perhitungan_2
                                                      WHERE dt_perhitungan_2.id_perhitungan_1 = dt_perhitungan_1.id) as nilai_sub')
                                        ->orderby('dt_rekening_perhitungan.nama')
                                        ->with(array(
                                            'perhitungan_2' => function($perhitungan_2){
                                                $perhitungan_2->leftjoin('dt_rekening_perhitungan','dt_perhitungan_2.id_rekening','=','dt_rekening_perhitungan.id')
                                                ->selectRaw('dt_perhitungan_2.id,
                                                             dt_perhitungan_2.id_perhitungan,
                                                             dt_perhitungan_2.id_perhitungan_1,
                                                             dt_perhitungan_2.id_rekening,
                                                             dt_perhitungan_2.nilai,
                                                             dt_rekening_perhitungan.nama,

                                                             (SELECT SUM(dt_perhitungan_3.nilai)
                                                              FROM dt_perhitungan_3
                                                              WHERE dt_perhitungan_3.id_perhitungan_2 = dt_perhitungan_2.id) as nilai_sub')
                                                
                                                ->orderby('dt_rekening_perhitungan.nama')
                                                ->with(array(
                                                    'perhitungan_3' => function($perhitungan_3){
                                                        $perhitungan_3->leftjoin('dt_rekening_perhitungan','dt_perhitungan_3.id_rekening','=','dt_rekening_perhitungan.id')
                                                        ->selectRaw('dt_perhitungan_3.id,
                                                                     dt_perhitungan_3.id_perhitungan,
                                                                     dt_perhitungan_3.id_perhitungan_1,
                                                                     dt_perhitungan_3.id_perhitungan_2,
                                                                     dt_perhitungan_3.id_rekening,
                                                                     dt_perhitungan_3.nilai,
                                                                     dt_rekening_perhitungan.nama')
                                                        ->orderby('dt_rekening_perhitungan.nama');
                                                    },
                                                ));
                                            },
                                        ));
                                    },
                                ))
                                ->get();
        } else {
            $jns    = '';
            $rwt    = '';
            $perhitungan    = '';            
        }

        $jasa       = DB::table('dt_jasa')
                        ->where('hapus',0)
                        ->orderby('jasa')
                        ->get();

        $rekening   = DB::table('dt_rekening_perhitungan')
                        ->where('id',1)
                        ->where('hapus',0)
                        ->orwhere('id',2)
                        ->where('hapus',0)
                        ->orwhere('id',14)
                        ->where('hapus',0)
                        ->get();

        $rekening_1   = DB::table('dt_rekening_perhitungan')
                        ->where('id',3)
                        ->where('hapus',0)
                        ->orwhere('id',4)
                        ->where('hapus',0)
                        ->get();

        $rekening_2   = DB::table('dt_rekening_perhitungan')
                        ->where('id','<>',1)
                        ->where('id','<>',2)
                        ->where('id','<>',14)
                        ->where('id','<>',3)
                        ->where('id','<>',4)
                        ->where('hapus',0)
                        ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.tarif_daftar',compact('jns','jenis','perhitungan','jasa','rekening','rekening_1','rekening_2','rwt','rawat'));
        } else {
           return view('tarif_daftar',compact('jns','jenis','perhitungan','jasa','rekening','rekening_1','rekening_2','rwt','rawat'));
        }        
    }

    public function tarif_salin(request $request){
      $tarif    = DB::table('dt_perhitungan')
                    ->where('id_pasien_jenis',$request->jenis)
                    ->where('id_pasien_jenis_rawat',$request->rawat)
                    ->where('dt_perhitungan.hapus',0)
                    ->get();

      foreach($tarif as $trf){
        DB::table('dt_perhitungan')
          ->insert([
            'id_pasien_jenis' => $request->baru_jenis,
            'id_pasien_jenis_rawat' => $request->baru_rawat,
            'id_kategori_jasa' => $trf->id_kategori_jasa,
            'id_jasa' => $trf->id_jasa,
            'petugas_update' => Auth::user()->id,
            'petugas_create' => Auth::user()->id,
          ]);

        $trf_baru   = DB::table('dt_perhitungan')
                        ->where('id_pasien_jenis',$request->baru_jenis)
                        ->where('id_pasien_jenis_rawat',$request->baru_rawat)
                        ->orderby('dt_perhitungan.id','desc')
                        ->first();

        $tarif_1  = DB::table('dt_perhitungan_1')
                      ->where('id_perhitungan',$trf->id)
                      ->get();

        foreach($tarif_1 as $trf_1){
          DB::table('dt_perhitungan_1')
            ->insert([
              'id_perhitungan' => $trf_baru->id,
              'id_rekening' => $trf_1->id_rekening,
              'nilai' => $trf_1->nilai,
              'petugas_update' => Auth::user()->id,
              'petugas_create' => Auth::user()->id,
            ]);

          $trf_baru_1   = DB::table('dt_perhitungan_1')
                            ->where('id_perhitungan',$trf_baru->id)
                            ->orderby('dt_perhitungan_1.id','desc')
                            ->first();

          $tarif_2  = DB::table('dt_perhitungan_2')
                        ->where('id_perhitungan',$trf->id)
                        ->where('id_perhitungan_1',$trf_1->id)
                        ->get();

          foreach($tarif_2 as $trf_2){
            DB::table('dt_perhitungan_2')
              ->insert([
                'id_perhitungan' => $trf_baru->id,
                'id_perhitungan_1' => $trf_baru_1->id,
                'id_rekening' => $trf_2->id_rekening,
                'nilai' => $trf_2->nilai,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
              ]);

            $trf_baru_2   = DB::table('dt_perhitungan_2')
                              ->where('id_perhitungan',$trf_baru->id)
                              ->where('id_perhitungan_1',$trf_baru_1->id)
                              ->orderby('dt_perhitungan_2.id','desc')
                              ->first();

            $tarif_3  = DB::table('dt_perhitungan_3')
                          ->where('id_perhitungan',$trf->id)
                          ->where('id_perhitungan_1',$trf_1->id)
                          ->where('id_perhitungan_2',$trf_2->id)
                          ->get();

            foreach($tarif_3 as $trf_3){
              DB::table('dt_perhitungan_3')
                ->insert([
                  'id_perhitungan' => $trf_baru->id,
                  'id_perhitungan_1' => $trf_baru_1->id,
                  'id_perhitungan_2' => $trf_baru_2->id,
                  'id_rekening' => $trf_3->id_rekening,
                  'nilai' => $trf_3->nilai,
                  'petugas_update' => Auth::user()->id,
                  'petugas_create' => Auth::user()->id,
                ]);
            }
          }
        }
      }

      return redirect()->back()->with('success','Tarif berhasil disalin.');;
    }

    public function tarif_user(request $request){
        $jenis  = DB::table('dt_ruang_jenis')
                  ->leftjoin('dt_pasien_jenis','dt_ruang_jenis.id_pasien_jenis','=','dt_pasien_jenis.id')                  
                  ->selectRaw('dt_ruang_jenis.id_pasien_jenis as id,
                               dt_pasien_jenis.jenis')
                  ->where('dt_ruang_jenis.aktif',1)
                  ->where('dt_ruang_jenis.id_ruang',Auth::user()->id_ruang)
                  ->get();

        $rawat  = DB::table('dt_pasien_jenis_rawat')->get();

        if($request->has('jns') && $request->has('rwt')){
            $jns    = $request->jns;
            $rwt    = $request->rwt;

            $perhitungan    = dt_perhitungan::leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_perhitungan.id,
                                             dt_perhitungan.id_pasien_jenis,
                                             dt_perhitungan.id_kategori_jasa,
                                             dt_perhitungan.id_jasa,
                                             dt_kategori_jasa.kategori,
                                             dt_jasa.jasa')
                                ->where('dt_perhitungan.id_pasien_jenis',$jns)
                                ->where('dt_perhitungan.id_pasien_jenis_rawat',$rwt)
                                ->where('dt_perhitungan.hapus',0)
                                ->orderby('dt_jasa.jasa')
                                ->with(array(
                                    'perhitungan_1' => function($perhitungan_1){
                                        $perhitungan_1->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                        ->selectRaw('dt_perhitungan_1.id,
                                                     dt_perhitungan_1.id_perhitungan,
                                                     dt_perhitungan_1.id_rekening,
                                                     dt_perhitungan_1.nilai,
                                                     dt_rekening_perhitungan.nama')
                                        ->orderby('dt_rekening_perhitungan.nama')
                                        ->with(array(
                                            'perhitungan_2' => function($perhitungan_2){
                                                $perhitungan_2->leftjoin('dt_rekening_perhitungan','dt_perhitungan_2.id_rekening','=','dt_rekening_perhitungan.id')
                                                ->selectRaw('dt_perhitungan_2.id,
                                                             dt_perhitungan_2.id_perhitungan,
                                                             dt_perhitungan_2.id_perhitungan_1,
                                                             dt_perhitungan_2.id_rekening,
                                                             dt_perhitungan_2.nilai,
                                                             dt_rekening_perhitungan.nama')
                                                ->orderby('dt_rekening_perhitungan.nama')
                                                ->with(array(
                                                    'perhitungan_3' => function($perhitungan_3){
                                                        $perhitungan_3->leftjoin('dt_rekening_perhitungan','dt_perhitungan_3.id_rekening','=','dt_rekening_perhitungan.id')
                                                        ->selectRaw('dt_perhitungan_3.id,
                                                                     dt_perhitungan_3.id_perhitungan,
                                                                     dt_perhitungan_3.id_perhitungan_1,
                                                                     dt_perhitungan_3.id_perhitungan_2,
                                                                     dt_perhitungan_3.id_rekening,
                                                                     dt_perhitungan_3.nilai,
                                                                     dt_rekening_perhitungan.nama')
                                                        ->orderby('dt_rekening_perhitungan.nama');
                                                    },
                                                ));
                                            },
                                        ));
                                    },
                                ))
                                ->get();
        } else {
            $jns    = '';
            $rwt    = '';
            $perhitungan    = '';            
        }

        $jasa       = DB::table('dt_jasa')
                        ->where('hapus',0)
                        ->orderby('jasa')
                        ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          return view('mobile.tarif_user',compact('jns','jenis','perhitungan','jasa','rawat','rwt'));
        } else {
          return view('tarif_user',compact('jns','jenis','perhitungan','jasa','rawat','rwt'));
        }
    }

    public function tarif_cetak(request $request){
      $jenis  = DB::table('dt_pasien_jenis')
                  ->where('hapus',0)
                  ->where('id',$request->jns)
                  ->first();

      $rawat  = DB::table('dt_pasien_jenis_rawat')->where('id',$request->rwt)->first();

            $perhitungan    = dt_perhitungan::leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_perhitungan.id,
                                             dt_perhitungan.id_pasien_jenis,
                                             dt_perhitungan.id_kategori_jasa,
                                             dt_perhitungan.id_jasa,
                                             dt_kategori_jasa.kategori,
                                             dt_jasa.jasa')
                                ->where('dt_perhitungan.id_pasien_jenis',$request->jns)
                                ->where('dt_perhitungan.id_pasien_jenis_rawat',$request->rwt)
                                ->where('dt_perhitungan.hapus',0)
                                ->orderby('dt_jasa.jasa')
                                ->with(array(
                                    'perhitungan_1' => function($perhitungan_1){
                                        $perhitungan_1->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                        ->selectRaw('dt_perhitungan_1.id,
                                                     dt_perhitungan_1.id_perhitungan,
                                                     dt_perhitungan_1.id_rekening,
                                                     dt_perhitungan_1.nilai,
                                                     dt_rekening_perhitungan.nama')
                                        ->orderby('dt_rekening_perhitungan.nama')
                                        ->with(array(
                                            'perhitungan_2' => function($perhitungan_2){
                                                $perhitungan_2->leftjoin('dt_rekening_perhitungan','dt_perhitungan_2.id_rekening','=','dt_rekening_perhitungan.id')
                                                ->selectRaw('dt_perhitungan_2.id,
                                                             dt_perhitungan_2.id_perhitungan,
                                                             dt_perhitungan_2.id_perhitungan_1,
                                                             dt_perhitungan_2.id_rekening,
                                                             dt_perhitungan_2.nilai,
                                                             dt_rekening_perhitungan.nama')
                                                ->orderby('dt_rekening_perhitungan.nama')
                                                ->with(array(
                                                    'perhitungan_3' => function($perhitungan_3){
                                                        $perhitungan_3->leftjoin('dt_rekening_perhitungan','dt_perhitungan_3.id_rekening','=','dt_rekening_perhitungan.id')
                                                        ->selectRaw('dt_perhitungan_3.id,
                                                                     dt_perhitungan_3.id_perhitungan,
                                                                     dt_perhitungan_3.id_perhitungan_1,
                                                                     dt_perhitungan_3.id_perhitungan_2,
                                                                     dt_perhitungan_3.id_rekening,
                                                                     dt_perhitungan_3.nilai,
                                                                     dt_rekening_perhitungan.nama')
                                                        ->orderby('dt_rekening_perhitungan.nama');
                                                    },
                                                ));
                                            },
                                        ));
                                    },
                                ))
                                ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
          return view('mobile.tarif_cetak',compact('perhitungan','jenis','rawat'));
        } else {
          return view('tarif_cetak',compact('perhitungan','jenis','rawat'));
        }
    }

    public function tarif(request $request){
        $jenis  = DB::table('dt_pasien_jenis')
                    ->where('hapus',0)
                    ->where('aktif',1)
                    ->get();

        $jasa   = DB::table('dt_jasa')
                    ->where('hapus',0)
                    ->orderby('jasa')
                    ->get();

        if($request->jns){
            $jns    = $request->jns;

            $perhitungan    = dt_perhitungan::leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_perhitungan.id,
                                             dt_perhitungan.id_jenis_pasien,
                                             dt_perhitungan.id_jasa,
                                             dt_jasa.jasa')
                                ->where('dt_perhitungan.id_jenis_pasien',$jns)
                                ->where('dt_perhitungan.hapus',0)
                                ->orderby('dt_jasa.jasa')
                                ->get();
        } else {
            $jns    = '';
            $perhitungan    = '';
        }

        $hitung     = '';
        $hitung_1   = '';
        $hitung_2   = '';

        return view('mobile.tarif',compact('jns','jenis','perhitungan','jasa','hitung','hitung_1','hitung_2'));
    }

    public function tarif_1($id){        
        $jenis              = DB::table('dt_pasien_jenis')
                                ->where('hapus',0)
                                ->where('aktif',1)
                                ->get();

        $hitung             = DB::table('dt_perhitungan')                                
                                ->where('dt_perhitungan.id',Crypt::decrypt($id))
                                ->first();

        $jns                = $hitung->id_jenis_pasien;

        $jasa               = DB::table('dt_jasa')
                                ->where('hapus',0)
                                ->orderby('jasa')
                                ->get();

        $rekening           = DB::table('dt_rekening_perhitungan')
                                ->where('hapus',0)
                                ->orderby('id')
                                ->get();

        $perhitungan        = DB::table('dt_perhitungan')
                                ->leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                ->selectRaw('dt_perhitungan.id,
                                             dt_perhitungan.id_jenis_pasien,
                                             dt_perhitungan.id_kategori_jasa,
                                             dt_perhitungan.id_jasa,
                                             dt_kategori_jasa.kategori,
                                             dt_jasa.jasa')
                                ->where('dt_perhitungan.id_jenis_pasien',$hitung->id_jenis_pasien)
                                ->where('dt_perhitungan.hapus',0)
                                ->orderby('dt_jasa.jasa')
                                ->get();

        $perhitungan_1      = DB::table('dt_perhitungan_1')
                                ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                ->selectRaw('dt_perhitungan_1.id,
                                             dt_perhitungan_1.id_perhitungan,
                                             dt_perhitungan_1.id_rekening,
                                             dt_perhitungan_1.nilai,
                                             dt_rekening_perhitungan.nama')
                                ->where('dt_perhitungan_1.id_perhitungan',$hitung->id)
                                ->orderby('dt_rekening_perhitungan.nama')
                                ->get();

        $hitung_1   = '';
        $hitung_2   = '';

        return view('mobile.tarif_1',compact('jenis','jns','hitung','perhitungan','perhitungan_1','jasa','rekening','hitung_1','hitung_2'));
    }

    public function tarif_2($id){
        $jenis              = DB::table('dt_pasien_jenis')
                                ->where('hapus',0)
                                ->where('aktif',1)
                                ->get();

        $hitung_1           = DB::table('dt_perhitungan_1')
                                ->where('dt_perhitungan_1.id',Crypt::decrypt($id))
                                ->first();

        $hitung             = DB::table('dt_perhitungan')                                
                                ->where('dt_perhitungan.id',$hitung_1->id_perhitungan)
                                ->first();

        $jns                = $hitung->id_jenis_pasien;

        $jasa               = DB::table('dt_jasa')
                                ->where('hapus',0)
                                ->orderby('jasa')
                                ->get();

        $rekening           = DB::table('dt_rekening_perhitungan')
                                ->where('hapus',0)
                                ->orderby('id')
                                ->get();

        $perhitungan        = DB::table('dt_perhitungan')
                                    ->leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                    ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                    ->selectRaw('dt_perhitungan.id,
                                                dt_perhitungan.id_jenis_pasien,
                                                dt_perhitungan.id_kategori_jasa,
                                                dt_perhitungan.id_jasa,
                                                dt_kategori_jasa.kategori,
                                                dt_jasa.jasa')
                                    ->where('dt_perhitungan.id_jenis_pasien',$hitung->id_jenis_pasien)
                                    ->where('dt_perhitungan.hapus',0)
                                    ->orderby('dt_jasa.jasa')
                                    ->get();

        $perhitungan_1      = DB::table('dt_perhitungan_1')
                                    ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                    ->selectRaw('dt_perhitungan_1.id,
                                                 dt_perhitungan_1.id_perhitungan,
                                                 dt_perhitungan_1.id_rekening,
                                                 dt_perhitungan_1.nilai,
                                                 dt_rekening_perhitungan.nama')
                                    ->where('dt_perhitungan_1.id_perhitungan',$hitung->id)
                                    ->orderby('dt_rekening_perhitungan.nama')
                                    ->get();

        $perhitungan_2      = DB::table('dt_perhitungan_2')
                                    ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_2.id_rekening','=','dt_rekening_perhitungan.id')
                                    ->selectRaw('dt_perhitungan_2.id,
                                                 dt_perhitungan_2.id_perhitungan,
                                                 dt_perhitungan_2.id_rekening,
                                                 dt_perhitungan_2.nilai,
                                                 dt_rekening_perhitungan.nama')
                                    ->where('dt_perhitungan_2.id_perhitungan_1',$hitung_1->id)
                                    ->orderby('dt_rekening_perhitungan.nama')
                                    ->get();

        $hitung_2   = '';

        return view('tarif_2',compact('jenis','jns','hitung','hitung_1','perhitungan','perhitungan_1','perhitungan_2','jasa','rekening','hitung_2'));
    }

    public function tarif_3($id){
        $jenis              = DB::table('dt_pasien_jenis')
                                ->where('hapus',0)
                                ->where('aktif',1)
                                ->get();

        $hitung_2           = DB::table('dt_perhitungan_2')
                                ->where('dt_perhitungan_2.id',Crypt::decrypt($id))
                                ->first();

        $hitung_1           = DB::table('dt_perhitungan_1')
                                ->where('dt_perhitungan_1.id',$hitung_2->id_perhitungan_1)
                                ->first();

        $hitung             = DB::table('dt_perhitungan')                                
                                ->where('dt_perhitungan.id',$hitung_1->id_perhitungan)
                                ->first();

        $jns                = $hitung->id_jenis_pasien;

        $jasa               = DB::table('dt_jasa')
                                ->where('hapus',0)
                                ->orderby('jasa')
                                ->get();

        $rekening           = DB::table('dt_rekening_perhitungan')
                                ->where('hapus',0)
                                ->orderby('id')
                                ->get();

        $perhitungan        = DB::table('dt_perhitungan')
                                    ->leftjoin('dt_kategori_jasa','dt_perhitungan.id_kategori_jasa','=','dt_kategori_jasa.id')
                                    ->leftjoin('dt_jasa','dt_perhitungan.id_jasa','=','dt_jasa.id')
                                    ->selectRaw('dt_perhitungan.id,
                                                dt_perhitungan.id_jenis_pasien,
                                                dt_perhitungan.id_kategori_jasa,
                                                dt_perhitungan.id_jasa,
                                                dt_kategori_jasa.kategori,
                                                dt_jasa.jasa')
                                    ->where('dt_perhitungan.id_jenis_pasien',$hitung->id_jenis_pasien)
                                    ->where('dt_perhitungan.hapus',0)
                                    ->orderby('dt_jasa.jasa')
                                    ->get();

        $perhitungan_1      = DB::table('dt_perhitungan_1')
                                    ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_1.id_rekening','=','dt_rekening_perhitungan.id')
                                    ->selectRaw('dt_perhitungan_1.id,
                                                 dt_perhitungan_1.id_perhitungan,
                                                 dt_perhitungan_1.id_rekening,
                                                 dt_perhitungan_1.nilai,
                                                 dt_rekening_perhitungan.nama')
                                    ->where('dt_perhitungan_1.id_perhitungan',$hitung->id)
                                    ->orderby('dt_rekening_perhitungan.nama')
                                    ->get();

        $perhitungan_2      = DB::table('dt_perhitungan_2')
                                    ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_2.id_rekening','=','dt_rekening_perhitungan.id')
                                    ->selectRaw('dt_perhitungan_2.id,
                                                 dt_perhitungan_2.id_perhitungan,
                                                 dt_perhitungan_2.id_rekening,
                                                 dt_perhitungan_2.nilai,
                                                 dt_rekening_perhitungan.nama')
                                    ->where('dt_perhitungan_2.id_perhitungan_1',$hitung_1->id)
                                    ->orderby('dt_rekening_perhitungan.nama')
                                    ->get();

        $perhitungan_3      = DB::table('dt_perhitungan_3')
                                    ->leftjoin('dt_rekening_perhitungan','dt_perhitungan_3.id_rekening','=','dt_rekening_perhitungan.id')
                                    ->selectRaw('dt_perhitungan_3.id,
                                                 dt_perhitungan_3.id_perhitungan,
                                                 dt_perhitungan_3.id_rekening,
                                                 dt_perhitungan_3.nilai,
                                                 dt_rekening_perhitungan.nama')
                                    ->where('dt_perhitungan_3.id_perhitungan_2',$hitung_2->id)
                                    ->orderby('dt_rekening_perhitungan.nama')
                                    ->get();

        return view('tarif_3',compact('jenis','jns','hitung','hitung_1','hitung_2','perhitungan','perhitungan_1','perhitungan_2','perhitungan_3','jasa','rekening'));
    }

    public function tarif_1_hapus($id){
        DB::table('dt_perhitungan')
            ->where('id',Crypt::decrypt($id))
            ->update([
                'hapus' => 1,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil dihapus.');
    }

    public function tarif_2_hapus($id){
        DB::table('dt_perhitungan_1')
            ->where('id',Crypt::decrypt($id))
            ->delete();

        return redirect()->back()->with('success','Data tarif berhasil dihapus.');
    }

    public function tarif_3_hapus($id){
        DB::table('dt_perhitungan_2')
            ->where('id',Crypt::decrypt($id))
            ->delete();

        return redirect()->back()->with('success','Data tarif berhasil dihapus.');
    }

    public function tarif_4_hapus($id){
        DB::table('dt_perhitungan_3')
            ->where('id',Crypt::decrypt($id))
            ->delete();

        return redirect()->back()->with('success','Data tarif berhasil dihapus.');
    }

    public function tarif_1_edit(request $request){
        DB::table('dt_perhitungan')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'id_jasa' => $request->id_jasa,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil disimpan.');
    }

    public function tarif_1_baru(request $request){
        DB::table('dt_perhitungan')
            ->insert([
                'id_pasien_jenis' => $request->id_pasien_jenis,
                'id_pasien_jenis_rawat' => $request->id_pasien_jenis_rawat,
                'id_jasa' => $request->id_jasa,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil ditambahkan.');
    }    

    public function tarif_2_edit(request $request){
        DB::table('dt_perhitungan_1')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil disimpan.');
    }

    public function tarif_2_baru(request $request){
        DB::table('dt_perhitungan_1')
            ->insert([
                'id_perhitungan' => $request->id_perhitungan,
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil ditambahkan.');
    }

    public function tarif_3_edit(request $request){
        DB::table('dt_perhitungan_2')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil disimpan.');
    }

    public function tarif_3_baru(request $request){
        DB::table('dt_perhitungan_2')
            ->insert([
                'id_perhitungan' => $request->id_perhitungan,
                'id_perhitungan_1' => $request->id_perhitungan_1,
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil ditambahkan.');
    }

    public function tarif_4_edit(request $request){
        DB::table('dt_perhitungan_3')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil disimpan.');
    }

    public function tarif_4_baru(request $request){
        DB::table('dt_perhitungan_3')
            ->insert([
                'id_perhitungan' => $request->id_perhitungan,
                'id_perhitungan_1' => $request->id_perhitungan_1,
                'id_perhitungan_2' => $request->id_perhitungan_2,
                'id_rekening' => $request->id_rekening,
                'nilai' => $request->nilai,
                'petugas_update' => Auth::user()->id,
                'petugas_create' => Auth::user()->id,
            ]);

        return redirect()->back()->with('success','Data tarif berhasil ditambahkan.');
    }

    public function kalkulasi_ulang(){
        $cek    = DB::table('control')->first();

        if($cek->bpjs == 0 && $cek->remun == 0 && $cek->kalkulasi_jasa == 0){
            DB::select('CALL kalkulasi_layanan();');            
            return back();
        } else {
            Toastr::error('Terdapat perhitungan Remunerasi atau Claim BPJS yang masih dalam proses.');
            return back();
        }
    }

    public function edit_tarif(request $request){
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

        if($request->id_ruang_sub){
            $id_ruang_sub   = $request->id_ruang_sub;
        } else {
            $id_ruang_sub   = '';
        }

        if($request->id_jenis){
            $id_jenis   = $request->id_jenis;
        } else {
            $id_jenis   = '';
        }

        if($request->id_dpjp){
            $id_dpjp   = $request->id_dpjp;
        } else {
            $id_dpjp   = '';
        }

        $ruang  = DB::table('dt_ruang')
                    ->where('hapus',0)
                    ->orderby('ruang')
                    ->get();

        $jenis  = DB::table('dt_pasien_jenis')
                    ->where('hapus',0)
                    ->get();        

        $dpjp   = DB::table('users')
                    ->where('id_tenaga',1)
                    ->where('hapus',0)
                    ->orderby('users.nama')
                    ->selectRaw('users.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            $tarif  = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien_jenis.jenis as jenis_pasien,
                                 dt_pasien.nama,
                                 dt_pasien.no_mr,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                 dt_jasa.jasa,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                  (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,
                                 dt_pasien_layanan.id_jasa,
                                 dt_pasien_layanan.tarif')
                    ->whereNotNull('dt_pasien_layanan.keluar')
                    ->whereDate('dt_pasien_layanan.keluar','>=',$awal)
                    ->whereDate('dt_pasien_layanan.keluar','<=',$akhir)

                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_jenis',$id_jenis);
                        })

                    ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang);
                        })

                    ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                    ->when($id_ruang_sub, function ($query) use ($id_ruang_sub) {
                            return $query->where('dt_pasien_layanan.id_ruang_sub',$id_ruang_sub);
                        })
                   
                    ->paginate(10);

            return view('mobile.edit_tarif',compact('awal','akhir','id_ruang','ruang','tarif','jenis','id_jenis','id_ruang_sub','dpjp','id_dpjp'));
        } else {
            $tarif  = DB::table('dt_pasien_layanan')
                    ->leftjoin('dt_pasien_jenis','dt_pasien_layanan.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->leftjoin('dt_jasa','dt_pasien_layanan.id_jasa','=','dt_jasa.id')
                    ->leftjoin('dt_pasien','dt_pasien_layanan.id_pasien','=','dt_pasien.id')
                    ->leftjoin('users','dt_pasien_layanan.id_dpjp','=','users.id')
                    ->selectRaw('dt_pasien_layanan.id,
                                 dt_pasien_jenis.jenis as jenis_pasien,
                                 dt_pasien.nama,
                                 dt_pasien.no_mr,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as dpjp,
                                 dt_jasa.jasa,
                                 (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang) as ruang,
                                  (SELECT dt_ruang.ruang
                                  FROM dt_ruang
                                  WHERE dt_ruang.id = dt_pasien_layanan.id_ruang_sub) as ruang_sub,
                                 dt_pasien_layanan.tarif,
                                 dt_pasien_layanan.id_jasa')
                    ->whereNotNull('dt_pasien_layanan.keluar')
                    ->whereDate('dt_pasien_layanan.keluar','>=',$awal)
                    ->whereDate('dt_pasien_layanan.keluar','<=',$akhir)

                    ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan.id_jenis',$id_jenis);
                        })

                    ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan.id_ruang',$id_ruang);
                        })

                    ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                        })

                    ->when($id_ruang_sub, function ($query) use ($id_ruang_sub) {
                            return $query->where('dt_pasien_layanan.id_ruang_sub',$id_ruang_sub);
                        })
                   
                    ->get();

           return view('edit_tarif',compact('awal','akhir','id_ruang','ruang','tarif','jenis','id_jenis','id_ruang_sub','dpjp','id_dpjp'));
        }        
    }

    public function edit_tarif_kolektif(request $request){
        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        if($request->id_ruang_sub){
            $id_ruang_sub   = $request->id_ruang_sub;
        } else {
            $id_ruang_sub   = '';
        }

        if($request->id_jenis){
            $id_jenis   = $request->id_jenis;
        } else {
            $id_jenis   = '';
        }

        if($request->id_dpjp){
            $id_dpjp   = $request->id_dpjp;
        } else {
            $id_dpjp   = '';
        }

        $layanan        = DB::table('dt_pasien_layanan')
                            ->selectRaw('dt_pasien_layanan.id,
                                         dt_pasien_layanan.tarif')
                            ->where('dt_pasien_layanan.tarif','>=',$request->dari)
                            ->where('dt_pasien_layanan.tarif','<=',$request->sampai)
                            ->whereNotNull('dt_pasien_layanan.keluar')
                            ->whereDate('dt_pasien_layanan.keluar','>=',$request->awal)
                            ->whereDate('dt_pasien_layanan.keluar','<=',$request->akhir)

                            ->when($id_ruang, function ($query) use ($id_ruang) {
                                return $query->where('dt_pasien_layanan.id_ruang',$id_ruang);
                            })

                            ->when($id_ruang_sub, function ($query) use ($id_ruang_sub) {
                                return $query->where('dt_pasien_layanan.id_ruang_sub',$id_ruang_sub);
                            })

                            ->when($id_jenis, function ($query) use ($id_jenis) {
                                return $query->where('dt_pasien_layanan.id_jenis',$id_jenis);
                            })

                            ->when($id_dpjp, function ($query) use ($id_dpjp) {
                                return $query->where('dt_pasien_layanan.id_dpjp',$id_dpjp);
                            })

                            ->where('dt_pasien_layanan.id_jasa','<>',40)
                            ->get();

        foreach($layanan as $lay){
            DB::table('dt_pasien_layanan')
                ->where('id',$lay->id)
                ->update([
                    'tarif' => $lay->tarif * ($request->persen / 100),
                    'petugas_update' => Auth::user()->id,
                ]);
        }        

        return back();
    }

    public function edit_tarif_simpan(request $request){
        DB::table('dt_pasien_layanan')
            ->where('id',Crypt::decrypt($request->id))
            ->update([
                'tarif' => $request->tarif,
                'petugas_update' => Auth::user()->id,
            ]);

        return back();
    }

    public function edit_tarif_hapus($id){
        DB::table('dt_pasien_layanan')
            ->where('id',Crypt::decrypt($id))
            ->delete();

        return back();
    }

    public function jenis_pasien(request $request){
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

      $jenis  = DB::table('dt_pasien_jenis')
                  ->where('hapus',0)
                  ->when($cari, function ($query) use ($cari) {
                    return $query->where('dt_pasien_jenis.jenis',$cari);
                  })
                  ->paginate($tampil);

      $agent = new Agent();
        
      if ($agent->isMobile()) {
        return view('mobile.jenis_pasien',compact('jenis','tampil','cari'));
      } else {
        return view('jenis_pasien',compact('jenis','tampil','cari'));
      }
    }

    public function jenis_pasien_hapus($id){
      DB::table('dt_pasien_jenis')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Jenis pasien berhasil dihapus.');
    }

    public function jenis_pasien_baru(request $request){
      DB::table('dt_pasien_jenis')
        ->insert([
          'jenis' => $request->jenis,
          'petugas_update' => Auth::user()->id,
          'petugas_create' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Jenis pasien berhasil ditambahkan.');
    }

    public function jenis_pasien_edit(request $request){
      DB::table('dt_pasien_jenis')
        ->where('id',$request->id)
        ->update([
          'jenis' => $request->jenis,
          'petugas_update' => Auth::user()->id,
        ]);

      return redirect()->back()->with('success','Jenis pasien berhasil disimpan.');
    }

    public function jenis_pasien_edit_show(request $request){
      $data   = DB::table('dt_pasien_jenis')
                  ->where('id',$request->id)
                  ->selectRaw('dt_pasien_jenis.id,
                               dt_pasien_jenis.jenis')
                  ->first();

      echo json_encode($data);
    }
}
