<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\dt_remun;
use App\dt_remun_detil;
use App\dt_pasien;
use App\dt_pasien_ruang;
use App\dt_pasien_layanan;
use App\Exports\RemunExport;
use App\Exports\RemunRincian;
use App\Exports\RemunPembayaran;
use App\Exports\RemunKwitansi;
use App\Exports\RemunOriginal;
use Excel;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class RemunerasiController extends Controller
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

    public function remunerasi_admin(){      
      $remun  = DB::table('dt_remun')
                  ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                  ->selectRaw('dt_remun.id,
                                DATE_FORMAT(dt_remun.tanggal, "%W, %d %M %Y") as tanggal,
                                DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,                                 
                                dt_remun.a_jp,
                                dt_remun.stat,
                                TIMEDIFF(dt_remun.selesai,dt_remun.mulai) as waktu,
                                dt_remun_status.status,
                                (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                 FROM users
                                 WHERE users.id = dt_remun.petugas_create) as petugas,
                                (SELECT dt_pasien_jenis.jenis
                                 FROM dt_pasien_jenis
                                 WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jkn')
                  ->where('dt_remun.stat','<',2)
                  ->where('dt_remun.hapus',0)
                  ->orderby('dt_remun.id','desc')
                  ->get();

      $bpjs       = DB::table('dt_claim_bpjs_stat')
                      ->leftjoin('dt_pasien_jenis','dt_claim_bpjs_stat.id_pasien_jenis','=','dt_pasien_jenis.id')
                      ->where('dt_claim_bpjs_stat.stat',1)
                      ->where('dt_claim_bpjs_stat.hapus',0)
                      ->selectRaw('dt_claim_bpjs_stat.id,
                                   DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                   DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                   dt_pasien_jenis.jenis,
                                   (SELECT SUM(dt_claim_bpjs.claim_jalan)+SUM(dt_claim_bpjs.claim_inap)
                                   FROM dt_claim_bpjs
                                   WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim')
                      ->get();

      $interensif = DB::table('users')
                        ->where('hapus',0)
                        ->where('id_tenaga',1)
                        ->orderby('nama')
                        ->get();

      $cek        = DB::table('control')->first();

      return view('remunerasi_admin',compact('remun','bpjs','interensif','cek'));
    }
    public function remunerasi(request $request){
      $cek        = DB::table('control')->first();
      $cek_remun  = DB::table('dt_remun')
                      ->where('stat','<',3)
                      ->where('hapus',0)
                      ->get();

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

      $remun      = DB::table('dt_remun')
                      ->where('dt_remun.id',$request->id)
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
                                   TIMEDIFF(dt_remun.selesai,dt_remun.mulai) as waktu,
                                   dt_remun.langkah')
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
                                    users.staf,
                                    users.jp_admin,  

                                    dt_remun_detil.perawat_setara,

                                    users_tenaga_bagian.id_tenaga,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.gelar_depan,
                                    users.gelar_belakang,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.medis as kel_medis,                                    
                                    
                                    dt_ruang.ruang,
                                    users_tenaga_bagian.bagian,                                    
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

                                     SUM(dt_remun_detil.total_indek) as total_indek,

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
        } else {
            $rincian = '';
            $jumlah = '';
        }

        $interensif = DB::table('users')
                        ->where('hapus',0)
                        ->where('id_tenaga_bagian',24)
                        ->orderby('nama')
                        ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi',compact('remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','cek_remun','interensif'));
        } else {
            return view('remunerasi',compact('remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian','cek_remun','interensif'));
        }
    }

    public function remunerasi_jasa(request $request){
      $remun = $request->id;

      $remun = $request->id;
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

      if($request->id_tenaga){
        $id_tenaga  = $request->id_tenaga;
      } else {
        $id_tenaga  = '';
      }

      $ruang      = DB::table('dt_ruang')
                      ->where('hapus',0)
                      ->orderby('ruang')
                      ->get();

      $tenaga     = DB::table('users_tenaga')
                      ->orderby('users_tenaga.id')
                      ->where('users_tenaga.id','<>',4)
                      ->where('users_tenaga.id','<>',5)
                      ->where('users_tenaga.id','<>',6)
                      ->get();

      $bagian     = DB::table('users_tenaga_bagian')
                      ->where('hapus',0)
                      ->orderby('bagian')
                      ->get();       

      return view('remunerasi_jasa',compact('remun','ruang','bagian','tenaga','id_bagian','id_ruang','id_tenaga'));
    }

    public function remunerasi_jasa_tampil(request $request){
      if($request->ajax()) {
        $output   ='';
        $jumlah   ='';

        $remun = $request->id;

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

        if($request->id_tenaga){
          $id_tenaga  = $request->id_tenaga;
        } else {
          $id_tenaga  = '';
        }

        if($request->asal){
          $asal  = $request->asal;
        } else {
          $asal  = '';
        }

        $cek  = DB::table('dt_remun_detil')
                  ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                  ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                  ->selectRaw('dt_remun_detil.id')
                  ->where('dt_remun_detil.id_remun',$remun)
                  ->where('users_tenaga_bagian.id_tenaga','<>',4)
                  ->where('users_tenaga_bagian.id_tenaga','<>',5)
                  ->where('users_tenaga_bagian.id_tenaga','<>',6)

                  ->when($id_ruang, function ($query) use ($id_ruang) {
                    return $query->where('users.id_ruang',$id_ruang);
                  })

                  ->when($id_bagian, function ($query) use ($id_bagian) {
                    return $query->where('users.id_tenaga_bagian',$id_bagian);
                  })

                  ->when($id_tenaga, function ($query) use ($id_tenaga) {
                    return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                  })

                  ->count();

        if($cek > 0){
          $jml       = DB::table('dt_remun_detil')
                          ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                          ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                          ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                          ->where('dt_remun_detil.id_remun',$remun)
                          ->where('users_tenaga_bagian.id_tenaga','<>',4)
                          ->where('users_tenaga_bagian.id_tenaga','<>',5)
                          ->where('users_tenaga_bagian.id_tenaga','<>',6)

                          ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                          })

                          ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                          })

                          ->when($id_tenaga, function ($query) use ($id_tenaga) {
                            return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                          })

                          ->first();

          if($request->relokasi){
            $relokasi  = str_replace(',','',$request->relokasi);
          } else {
            $relokasi  = 0;
          }

          if($request->alokasi){
            $alokasi  = str_replace(',','',$request->alokasi);
          } else {
            $alokasi  = 0;
          }

          $rincian    = DB::table('dt_remun_detil')
                          ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                          ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                          ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                          ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                          ->leftjoin('users_status','users.id_status','=','users_status.id')
                          ->selectRaw('dt_remun_detil.id,
                                      dt_remun_detil.score_real as score,
                                      dt_remun_detil.r_medis as r_medis_asal,
                                      (dt_remun_detil.r_medis/'.$jml->r_medis.') * ('.$jml->r_medis.'-'.$relokasi.'+'.$alokasi.') as r_medis,

                                      users_status.status,
                                      CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                      IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,

                                      dt_ruang.ruang,
                                      users_tenaga_bagian.bagian,
                                      users_tenaga_bagian.id_tenaga,
                                      users_tenaga.tenaga')
                          ->where('dt_remun_detil.id_remun',$remun)
                          ->where('users_tenaga_bagian.id_tenaga','<>',4)
                          ->where('users_tenaga_bagian.id_tenaga','<>',5)
                          ->where('users_tenaga_bagian.id_tenaga','<>',6)

                          ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                          })

                          ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                          })

                          ->when($id_tenaga, function ($query) use ($id_tenaga) {
                            return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                          })

                          ->orderby('users_tenaga_bagian.urut')
                          ->orderby('users.nama')
                          ->get();

          $no = 0;
          foreach ($rincian as $key => $rinc) { 
            $no = $no + 1;
            $output.='<tr>
                        <td class="min">
                          <button class="btn btn-info btn-mini edit" title="Edit Jasa Perorangan" data-toggle="modal" data-id="'.$rinc->id.'" data-awal="'.number_format($rinc->r_medis_asal,2).'" data-nama="'.$rinc->nama.'" data-target="#modal_jasa_edit">
                            <i class="icon-edit"></i>
                          </button>
                        </td>
                        <td style="text-align: right;">'.$no.'.</td>
                        <td>'.$rinc->nama.'</td>
                        <td>'.$rinc->ruang.'</td>
                        <td class="min">'.strtoupper($rinc->tenaga).'</td>
                        <td class="min">'.strtoupper($rinc->bagian).'</td>                      
                        <td>'.$rinc->status.'</td>              
                        <td style="text-align: right;">'.number_format($rinc->score,2).'</td>
                        <td style="text-align: right;">'.number_format($rinc->r_medis_asal,2).'</td>
                        <td style="text-align: right;">'.number_format($rinc->r_medis,2).'</td>
                      </tr>';
          }

          $jumlah   = number_format($jml->r_medis,2);
          $sisa     = number_format($jml->r_medis - $relokasi + $alokasi,2);
        } else {         
          $jumlah    = 0;
          $rincian   = '';
          $output   ='';
          $sisa     = 0;
        }

        $output.='<script type="text/javascript">
                    $(document).ready(function() {
                      $(".edit").on("click",function() {
                        var id        = $(this).attr("data-id");
                        var jasa_awal = $(this).attr("data-awal");
                        var nama      = $(this).attr("data-nama");

                        $("#edit_id").val(id);
                        $("#edit_jasa_awal").val(jasa_awal);
                        $("#exampleModalCenterTitle").html(nama);
                      });
                    });
                  </script>';

        $tandon   = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$remun)
                      ->selectRaw('FORMAT(dt_remun_tandon.sisa,2) as sisa')
                      ->orderby('dt_remun_tandon.id','DESC')
                      ->first();

        return response()->json(['output' => $output, 'jumlah' => $jumlah, 'tandon' => $tandon, 'sisa' => $sisa]);
      }
    }

    public function remunerasi_jasa_relokasi(request $request){
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

      if($request->id_tenaga){
        $id_tenaga  = $request->id_tenaga;
      } else {
        $id_tenaga  = '';
      }      

      $relokasi = str_replace(',','',$request->relokasi);

      $jml        = DB::table('dt_remun_detil')
                      ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                      ->where('dt_remun_detil.id_remun',$request->id)
                      ->where('users_tenaga_bagian.id_tenaga','<>',4)
                      ->where('users_tenaga_bagian.id_tenaga','<>',5)

                      ->when($id_ruang, function ($query) use ($id_ruang) {
                        return $query->where('users.id_ruang',$id_ruang);
                      })

                      ->when($id_bagian, function ($query) use ($id_bagian) {
                        return $query->where('users.id_tenaga_bagian',$id_bagian);
                      })

                      ->when($id_tenaga, function ($query) use ($id_tenaga) {
                        return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                      })

                      ->first();

      if($relokasi > 0 && $jml && $jml->r_medis >= $relokasi){
        $rincian    = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil.id,
                                     dt_remun_detil.r_medis,
                                     (dt_remun_detil.r_medis/'.$jml->r_medis.') * ('.$jml->r_medis.'-'.$relokasi.') as r_medis_baru,
                                     users_tenaga_bagian.id_tenaga')
                        ->where('dt_remun_detil.id_remun',$request->id)
                        ->where('users_tenaga_bagian.id_tenaga','<>',4)
                        ->where('users_tenaga_bagian.id_tenaga','<>',5)
                        ->where('dt_remun_detil.r_medis','>',0)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                          return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                          return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_tenaga, function ($query) use ($id_tenaga) {
                          return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                        })

                        ->get(); 

        foreach($rincian as $rinc){
          DB::table('dt_remun_tandon')
            ->insert([
              'id_remun' => $request->id,
              'id_remun_detil' => $rinc->id,
              'masuk' => $rinc->r_medis - $rinc->r_medis_baru,
              'petugas_update' => Auth::user()->id,
              'petugas_create' => Auth::user()->id,
            ]);

          DB::table('dt_remun_detil')
            ->where('dt_remun_detil.id',$rinc->id)
            ->update([
                'dt_remun_detil.r_medis' => $rinc->r_medis_baru,
            ]);
        }

        return redirect()->route('remunerasi_jasa',['id' => $request->id, 'id_bagian' => $id_bagian, 'id_ruang' => $id_ruang, 'id_tenaga' => $id_tenaga]);        
      } else {
        return redirect()->route('remunerasi_jasa',['id' => $request->id, 'id_bagian' => $id_bagian, 'id_ruang' => $id_ruang, 'id_tenaga' => $id_tenaga])->with('error','Tidak dapat melakukan penguragan jasa.');
      }
    }

    public function remunerasi_jasa_alokasi(request $request){
      $tandon   = DB::table('dt_remun_tandon')
                    ->where('id_remun',$request->id)
                    ->selectRaw('dt_remun_tandon.sisa')
                    ->orderby('dt_remun_tandon.id','desc')
                    ->first();

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

      if($request->id_tenaga){
        $id_tenaga  = $request->id_tenaga;
      } else {
        $id_tenaga  = '';
      }

      $alokasi = str_replace(',','',$request->alokasi);

      if($alokasi > 0 && $tandon->sisa - $alokasi >= -1){
        $jml        = DB::table('dt_remun_detil')
                        ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                        ->where('dt_remun_detil.id_remun',$request->id)
                        ->where('users_tenaga_bagian.id_tenaga','<>',4)
                        ->where('users_tenaga_bagian.id_tenaga','<>',5)

                        ->when($id_ruang, function ($query) use ($id_ruang) {
                          return $query->where('users.id_ruang',$id_ruang);
                        })

                        ->when($id_bagian, function ($query) use ($id_bagian) {
                          return $query->where('users.id_tenaga_bagian',$id_bagian);
                        })

                        ->when($id_tenaga, function ($query) use ($id_tenaga) {
                          return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                        })

                        ->first();

        if($tandon->sisa - $alokasi < 1){
          $rincian    = DB::table('dt_remun_detil')
                          ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                          ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                          ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                          ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                          ->leftjoin('users_status','users.id_status','=','users_status.id')
                          ->selectRaw('dt_remun_detil.id,
                                       dt_remun_detil.r_medis,
                                       (dt_remun_detil.r_medis/'.$jml->r_medis.') * ('.$jml->r_medis.'+'.$tandon->sisa.') as r_medis_baru,
                                       users_tenaga_bagian.id_tenaga')
                          ->where('dt_remun_detil.id_remun',$request->id)
                          ->where('users_tenaga_bagian.id_tenaga','<>',4)
                          ->where('users_tenaga_bagian.id_tenaga','<>',5)

                          ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                          })

                          ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                          })

                          ->when($id_tenaga, function ($query) use ($id_tenaga) {
                            return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                          })

                          ->get(); 
        } else {
          $rincian    = DB::table('dt_remun_detil')
                          ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                          ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                          ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                          ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                          ->leftjoin('users_status','users.id_status','=','users_status.id')
                          ->selectRaw('dt_remun_detil.id,
                                       dt_remun_detil.r_medis,
                                       (dt_remun_detil.r_medis/'.$jml->r_medis.') * ('.$jml->r_medis.'+'.$alokasi.') as r_medis_baru,
                                       users_tenaga_bagian.id_tenaga')
                          ->where('dt_remun_detil.id_remun',$request->id)
                          ->where('users_tenaga_bagian.id_tenaga','<>',4)
                          ->where('users_tenaga_bagian.id_tenaga','<>',5)

                          ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('users.id_ruang',$id_ruang);
                          })

                          ->when($id_bagian, function ($query) use ($id_bagian) {
                            return $query->where('users.id_tenaga_bagian',$id_bagian);
                          })

                          ->when($id_tenaga, function ($query) use ($id_tenaga) {
                            return $query->where('users_tenaga_bagian.id_tenaga',$id_tenaga);
                          })

                          ->get(); 
        }        

        foreach($rincian as $rinc){
          DB::table('dt_remun_tandon')
            ->insert([
              'id_remun' => $request->id,
              'id_remun_detil' => $rinc->id,
              'keluar' => $rinc->r_medis_baru - $rinc->r_medis,
              'petugas_update' => Auth::user()->id,
              'petugas_create' => Auth::user()->id,
            ]);          

          DB::table('dt_remun_detil')
            ->where('dt_remun_detil.id',$rinc->id)
            ->update([
                'dt_remun_detil.r_medis' => $rinc->r_medis_baru,
            ]);
        }

        return redirect()->route('remunerasi_jasa',['id' => $request->id, 'id_bagian' => $id_bagian, 'id_ruang' => $id_ruang, 'id_tenaga' => $id_tenaga]);        
      } else {
        return redirect()->route('remunerasi_jasa',['id' => $request->id, 'id_bagian' => $id_bagian, 'id_ruang' => $id_ruang, 'id_tenaga' => $id_tenaga])->with('error','Tidak dapat melakukan penambahan jasa.');
      }
    }

    public function remunerasi_jasa_edit(request $request){
      $data   = DB::table('dt_remun_detil')
                  ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                  ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                  ->where('dt_remun_detil.id',$request->id)
                  ->selectRaw('dt_remun_detil.id,
                               dt_remun_detil.id_remun,
                               dt_remun_detil.r_medis,
                               users.id_ruang,
                               users.id_tenaga_bagian,
                               users_tenaga_bagian.id_tenaga')
                  ->first();

      $jasa_baru = str_replace(',','',$request->jasa_baru);

      #Penambahan Jasa
      if($jasa_baru > $data->r_medis){
        $tandon   = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$data->id_remun)
                      ->orderby('dt_remun_tandon.id','desc')
                      ->selectRaw('dt_remun_tandon.sisa')
                      ->first();

        $penambahan = $jasa_baru - $data->r_medis;

        DB::table('dt_remun_tandon')
          ->insert([
            'id_remun' => $data->id_remun,
            'id_remun_detil' => $data->id,
            'keluar' => $penambahan,
            'petugas_create' => Auth::user()->id,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      #Pengurangan Jasa
      if($jasa_baru < $data->r_medis){
        $pengurangan = $data->r_medis - $jasa_baru;

        DB::table('dt_remun_tandon')
          ->insert([
            'id_remun' => $data->id_remun,
            'id_remun_detil' => $data->id,
            'masuk' => $pengurangan,
            'petugas_create' => Auth::user()->id,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      DB::table('dt_remun_detil')
        ->where('dt_remun_detil.id',$request->id)
        ->update([
          'r_medis' => $jasa_baru,
        ]);

      return redirect()->route('remunerasi_jasa',['id' => $data->id_remun]);
    }

    public function remunerasi_tandon($id){
      $tandon   = DB::table('dt_remun_tandon')
                    ->leftjoin('dt_remun_detil','dt_remun_tandon.id_remun_detil','=','dt_remun_detil.id')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->leftjoin('users_tenaga','users_tenaga_bagian.id_tenaga','=','users_tenaga.id')
                    ->selectRaw('dt_remun_tandon.id,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                 users_tenaga_bagian.bagian,
                                 users_tenaga.tenaga,
                                 dt_remun_tandon.masuk,
                                 dt_remun_tandon.keluar,
                                 dt_remun_tandon.sisa,
                                 dt_remun_tandon.keterangan')
                    ->where('dt_remun_tandon.id_remun',Crypt::decrypt($id))
                    ->orderby('dt_remun_tandon.id')
                    ->get();

      return view('remunerasi_tandon',compact('tandon'));
    }

    public function remunerasi_farmasi($id){
      $total    = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                    ->where('users.id_ruang',30)
                    ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                    ->first();

      $remun    = Crypt::decrypt($id);

      return view('remunerasi_farmasi',compact('total','remun'));
    }

    public function remunerasi_farmasi_jasa(request $request){
      if($request->ajax()) {
        $output='';

        $lama     = DB::table('dt_remun_detil')
                      ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                      ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                      ->where('users.id_ruang',30)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->first();

        $farmasi  = DB::table('dt_remun_detil')
                      ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('dt_remun_detil.id,
                                   dt_remun_detil.id_remun,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),",   ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                   users_tenaga_bagian.bagian,                                    
                                   dt_remun_detil.r_medis,
                                   (dt_remun_detil.r_medis / '.$lama->r_medis.') * '.str_replace(',','',$request->baru).' as r_medis_baru')
                      ->where('users.id_ruang',30)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->get();

        $no = 0;

        foreach ($farmasi as $key => $farm) { 
          $no = $no + 1;
          $output.='
                    <tr>
                      <td class="min" style="text-align: right; padding-right: 10px;">'.$no.'.</td>
                      <td>'.$farm->nama.'</td>
                      <td>'.$farm->bagian.'</td>
                      <td class="min" style="text-align: right;">'.number_format($farm->r_medis,2).'</td>
                      <td class="min" style="text-align: right;">'.number_format($farm->r_medis_baru,2).'</td>
                    </tr>';
        }

        return response($output);
      }      
    }

    public function remunerasi_farmasi_simpan(request $request){
      DB::select('CALL remun_farmasi('.$request->id_remun.','.str_replace(',','',$request->lama).','.str_replace(',','',$request->baru).');');

      return redirect()->route('remunerasi');
    }

    public function remunerasi_umum($id){
      $total    = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                    ->where('users.id_tenaga_bagian',2)
                    ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                    ->where('users.staf',0)
                    ->orwhere('users.id_tenaga_bagian',3)
                    ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                    ->where('users.staf',0)
                    ->first();

      $remun    = Crypt::decrypt($id);

      return view('remunerasi_umum',compact('total','remun'));
    }

    public function remunerasi_umum_jasa(request $request){
      if($request->ajax()) {
        $output='';        

        $lama     = DB::table('dt_remun_detil')
                      ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                      ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                      ->where('users.id_tenaga_bagian',2)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->where('users.staf',0)
                      ->orwhere('users.id_tenaga_bagian',3)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->where('users.staf',0)
                      ->first();

        $farmasi  = DB::table('dt_remun_detil')
                      ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                      ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                      ->selectRaw('dt_remun_detil.id,
                                   dt_remun_detil.id_remun,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                   IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),",   ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                   users_tenaga_bagian.bagian,                                    
                                   dt_remun_detil.r_medis,
                                   (dt_remun_detil.r_medis / '.$lama->r_medis.') * '.str_replace(',','',$request->baru).' as r_medis_baru')
                      ->where('users.id_tenaga_bagian',2)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->where('users.staf',0)
                      ->orwhere('users.id_tenaga_bagian',3)
                      ->where('dt_remun_detil.id_remun',$request->id_remun)
                      ->where('users.staf',0)
                      ->get();

        $no = 0;

        foreach ($farmasi as $key => $farm) { 
          $no = $no + 1;
          $output.='
                    <tr>
                      <td class="min" style="text-align: right; padding-right: 10px;">'.$no.'.</td>
                      <td>'.$farm->nama.'</td>
                      <td>'.$farm->bagian.'</td>
                      <td style="text-align: right;">'.number_format($farm->r_medis,2).'</td>
                      <td style="text-align: right;">'.number_format($farm->r_medis_baru,2).'</td>
                    </tr>';
        }

        return response($output);
      }      
    }

    public function remunerasi_umum_simpan(request $request){
      DB::select('CALL remun_dokterumum('.$request->id_remun.','.str_replace(',','',$request->lama).','.str_replace(',','',$request->baru).');');

      return redirect()->route('remunerasi');
    }

    public function relokasi_apotik(request $request){
      $asal   = DB::table('dt_remun')
                  ->where('id',Crypt::decrypt($request->id))
                  ->first();

      $alokasi_apotik = str_replace(',','',$request->alokasi_apotik);

      DB::table('dt_remun')
        ->where('id',Crypt::decrypt($request->id))
        ->update([
          'alokasi_apotik' => $asal->alokasi_apotik + $alokasi_apotik,
          'petugas_update' => Auth::user()->id,
        ]);

      $lama     = $request->asal;
      $baru     = $request->asal - $alokasi_apotik;

      $apotik   = DB::table('dt_remun_detil')
                    ->where('id_remun',Crypt::decrypt($request->id))
                    ->where('id_ruang',$request->id_ruang)
                    ->get();

      foreach($apotik as $apt){
        DB::table('dt_remun_detil')
          ->where('id',$apt->id)
          ->update([
            'r_medis' => $apt->r_medis * ($baru / $lama),
            'r_medis_asal' => $apt->r_medis,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return back();
    }

    public function tambahan_jasa(request $request){
      $alokasi_apotik = str_replace(',','',$request->alokasi_apotik);

      $relokasi = DB::table('dt_remun_detil')
                          ->where('dt_remun_detil.id_remun',$request->id_remun)
                          ->where('dt_remun_detil.id','<>',$request->id)
                          ->selectRaw('SUM(dt_remun_detil.alokasi_apotik) as alokasi_apotik')
                          ->first();

      $remun    = DB::table('dt_remun')
                    ->where('id',$request->id_remun)
                    ->selectRaw('alokasi_apotik')
                    ->first();

      if($alokasi_apotik <= ($remun->alokasi_apotik - $relokasi->alokasi_apotik)) {
        DB::table('dt_remun_detil')
          ->where('id',$request->id)
          ->update([
            'alokasi_apotik' => $alokasi_apotik,
            'petugas_update' => Auth::user()->id,
          ]);

        return back();
      } else {
        return back()->with('gagal','Tambahan jasa melebihi sisa relokasi jasa.');
      }      
    }

    public function reset_apotik($id){
      DB::table('dt_remun')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'alokasi_apotik' => 0,
          'petugas_update' => Auth::user()->id,
        ]);

      DB::table('dt_remun_detil')
        ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
        ->update([
          'alokasi_apotik' => 0,
          'petugas_update' => Auth::user()->id,
        ]);

      $relokasi   = DB::table('dt_remun_detil')
                      ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                      ->where('dt_remun_detil.r_medis_asal','>',0)
                      ->get();

      foreach($relokasi as $rel){
        DB::table('dt_remun_detil')
          ->where('id',$rel->id)
          ->update([
            'r_medis' => $rel->r_medis_asal,
            'r_medis_asal' => 0,
            'petugas_update' => Auth::user()->id,
          ]);
      }

      return back();
    }

    public function remunerasi_reset($id){      
      DB::select('CALL remun_reset('.Crypt::decrypt($id).');');
      return back();
    }

    public function remunerasi_reset_admin($id){      
      DB::select('CALL remun_reset_admin('.Crypt::decrypt($id).');');
      return back();
    }

    public function remunerasi_batal($id){
      $cek  = DB::table('dt_remun')
                ->where('id',Crypt::decrypt($id))
                ->first();

      if($cek->id_bpjs){
        DB::table('dt_claim_bpjs_stat')
          ->where('id',$cek->id_bpjs)
          ->update([
            'stat' => 1,
          ]);
      }

      DB::table('dt_remun_detil')
        ->where('id_remun',Crypt::decrypt($id))
        ->delete();

      DB::table('dt_remun')
          ->where('id',Crypt::decrypt($id))
          ->delete();

      DB::table('dt_remun_back')
        ->where('id',Crypt::decrypt($id))
        ->update([
          'hapus' => 1,
          'petugas_update' => Auth::user()->id,
        ]);

      if(Auth::user()->id_akses == 1){
        return redirect()->route('remunerasi_admin');
      } else {
        return back();
      }      
    }

    public function remunerasi_hitung(request $request){
      $cek    = DB::table('control')->first();

      $cek_remun = DB::table('dt_remun')
                      ->where('dt_remun.stat','<',1)
                      ->where('dt_remun.hapus',0)
                      ->get();

      if($cek->bpjs == 0 && $cek->remun == 0 && $cek->kalkulasi_jasa == 0 && count($cek_remun) == 0){        
        DB::table('dt_remun')
          ->where('dt_remun.stat','<',1)
          ->delete();        

        if($request->id_interhensif_1){
          $id_interhensif_1 = $request->id_interhensif_1;
        } else {
          $id_interhensif_1 = 'NULL';
        }

        if($request->id_interhensif_2){
          $id_interhensif_2 = $request->id_interhensif_2;
        } else {
          $id_interhensif_2 = 'NULL';
        }

        if($request->nominal_interhensif_1){
          $nominal_interhensif_1 = str_replace(',','',$request->nominal_interhensif_1);
        } else {
          $nominal_interhensif_1 = 0;
        }

        if($request->nominal_interhensif_2){
          $nominal_interhensif_2 = str_replace(',','',$request->nominal_interhensif_2);
        } else {
          $nominal_interhensif_2 = 0;
        }

        $keuangan = str_replace(',','',$request->keuangan);

        if($request->jenis == 1){
          DB::select('CALL remun_umum("'.$request->awal.'","'.$request->akhir.'",'.$request->tpp.','.$keuangan.','.$id_interhensif_1.','.$nominal_interhensif_1.','.$id_interhensif_2.','.$nominal_interhensif_2.','.Auth::user()->id.');');
        } else {
          DB::select('CALL remun_bpjs('.$request->bpjs.','.$request->tpp.','.$keuangan.','.$id_interhensif_1.','.$nominal_interhensif_1.','.$id_interhensif_2.','.$nominal_interhensif_2.','.Auth::user()->id.');');
        }

        return back();
      } else {
        Toastr::error('Terdapat perhitungan Claim BPJS atau Kalkulasi Jasa Layanan yang masih dalam proses.');
        return back();
      }
    }

    public function remunerasi_hitung_penyesuaian($id){
        $cek    = DB::table('control')->first();
        $cek_remun = DB::table('dt_remun')
                        ->where('stat','<',5)
                        ->where('hapus',0)
                        ->get();

        if($cek->bpjs == 0 && $cek->remun == 0 && $cek->kalkulasi_jasa == 0 && count($cek_remun) == 0){
            DB::select('CALL remun_penyesuaian('.Crypt::decrypt($id).');');
            return back();
        } else {
            Toastr::error('Terdapat perhitungan Claim BPJS atau Kalkulasi Jasa Layanan yang masih dalam proses.');
            return back();
        }
    }

    public function remunerasi_detil($id){
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

                                 dt_remun_detil.r_total_indek,
                                 dt_remun_detil.r_medis,
                                 dt_remun_detil.titipan,
                                 dt_remun_detil.r_jasa_pelayanan,
                                 dt_remun_detil.medis_dokter,
                                 dt_remun_detil.nominal_pajak,
                                 dt_remun_detil.sisa,

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
                    ->where('dt_remun_detil.id',Crypt::decrypt($id))
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
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima +
                                     dt_pasien_layanan_remun.jasa_anastesi_min,0)) +

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
                                     dt_pasien_layanan_remun.real_jasa_anastesi,0)) as real_anastesi,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi,0)) as claim_anastesi,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima,0)) as anastesi_diterima,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_min,0)) as min_anastesi,
                                     SUM(IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima + dt_pasien_layanan_remun.jasa_anastesi_min,0)) as anastesi,

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
                                     DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d %b %Y") as tanggal,
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
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima,0) as jasa_anastesi_diterima,
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_min,0) as min_anastesi,
                                     IF(dt_pasien_layanan_remun.id_anastesi = '.$detil->id_karyawan.',
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima + dt_pasien_layanan_remun.jasa_anastesi_min,0) as jasa_anastesi,

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
                                     dt_pasien_layanan_remun.jasa_anastesi_diterima + dt_pasien_layanan_remun.jasa_anastesi_min,0) +

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
                                     dt_pasien_layanan_remun.jasa_rr_diterima,0))) as jasa_medis')
                        
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
        if($detil->r_medis > 0){
        $tot   = DB::table('dt_pasien_layanan_jasa')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('SUM(dt_pasien_layanan_jasa.medis_perawat)+
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi)+
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1)+
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2)+
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen)+
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler)+
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker)+
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker)+
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi)+
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran)+
                                   SUM(dt_pasien_layanan_jasa.medis_fisio) as total')
                      ->first();

        $total     = DB::table('dt_pasien_layanan_jasa')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('SUM(dt_pasien_layanan_jasa.medis_perawat) as medis_perawat,
                                   SUM(dt_pasien_layanan_jasa.medis_perawat)*('.$detil->r_medis.'/'.$tot->total.') as jasa_perawat,
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi) as medis_pen_anastesi,
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi)*('.$detil->r_medis.'/'.$tot->total.') as jasa_pen_anastesi,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1) as medis_per_asisten_1,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1)*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_1,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2) as medis_per_asisten_2,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2)*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_2,
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen) as medis_instrumen,
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen)*('.$detil->r_medis.'/'.$tot->total.') as jasa_instrumen,
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler) as medis_sirkuler,
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler)*('.$detil->r_medis.'/'.$tot->total.') as jasa_sirkuler,
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker) as medis_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker)*('.$detil->r_medis.'/'.$tot->total.') as jasa_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker) as medis_ass_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker)*('.$detil->r_medis.'/'.$tot->total.') as jasa_ass_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi) as medis_admin_farmasi,
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi)*('.$detil->r_medis.'/'.$tot->total.') as jasa_admin_farmasi,
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran) as medis_pemulasaran,
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran)*('.$detil->r_medis.'/'.$tot->total.') as jasa_pemulasaran,
                                   SUM(dt_pasien_layanan_jasa.medis_fisio) as medis_fisio,
                                   SUM(dt_pasien_layanan_jasa.medis_fisio)*('.$detil->r_medis.'/'.$tot->total.') as jasa_fisio')
                      ->first();

        $rincian  = DB::table('dt_pasien_layanan_jasa')
                      ->leftjoin('dt_ruang','dt_pasien_layanan_jasa.id_ruang_sub','=','dt_ruang.id')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('dt_ruang.ruang,
                                   dt_pasien_layanan_jasa.medis_perawat,
                                   dt_pasien_layanan_jasa.medis_perawat*('.$detil->r_medis.'/'.$tot->total.') as jasa_perawat,
                                   dt_pasien_layanan_jasa.medis_pen_anastesi,
                                   dt_pasien_layanan_jasa.medis_pen_anastesi*('.$detil->r_medis.'/'.$tot->total.') as jasa_pen_anastesi,
                                   dt_pasien_layanan_jasa.medis_per_asisten_1,
                                   dt_pasien_layanan_jasa.medis_per_asisten_1*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_1,
                                   dt_pasien_layanan_jasa.medis_per_asisten_2,
                                   dt_pasien_layanan_jasa.medis_per_asisten_2*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_2,
                                   dt_pasien_layanan_jasa.medis_instrumen,
                                   dt_pasien_layanan_jasa.medis_instrumen*('.$detil->r_medis.'/'.$tot->total.') as jasa_instrumen,
                                   dt_pasien_layanan_jasa.medis_sirkuler,
                                   dt_pasien_layanan_jasa.medis_sirkuler*('.$detil->r_medis.'/'.$tot->total.') as jasa_sirkuler,
                                   dt_pasien_layanan_jasa.medis_apoteker,
                                   dt_pasien_layanan_jasa.medis_apoteker*('.$detil->r_medis.'/'.$tot->total.') as jasa_apoteker,
                                   dt_pasien_layanan_jasa.medis_ass_apoteker,
                                   dt_pasien_layanan_jasa.medis_ass_apoteker*('.$detil->r_medis.'/'.$tot->total.') as jasa_ass_apoteker,
                                   dt_pasien_layanan_jasa.medis_admin_farmasi,
                                   dt_pasien_layanan_jasa.medis_admin_farmasi*('.$detil->r_medis.'/'.$tot->total.') as jasa_admin_farmasi,
                                   dt_pasien_layanan_jasa.medis_pemulasaran,
                                   dt_pasien_layanan_jasa.medis_pemulasaran*('.$detil->r_medis.'/'.$tot->total.') as jasa_pemulasaran,
                                   dt_pasien_layanan_jasa.medis_fisio,
                                   dt_pasien_layanan_jasa.medis_fisio*('.$detil->r_medis.'/'.$tot->total.') as jasa_fisio')
                      ->get();

        $jasa_real = '';
      } else {
        $total  = '';
        $rincian = '';
        $jasa_real = '';
      }
      }

      $tandon     = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$detil->id_remun)
                      ->where('dt_remun_tandon.id_remun_detil',$detil->id)
                      ->orderby('dt_remun_tandon.id')
                      ->get();

      $total_tandon     = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$detil->id_remun)
                      ->where('dt_remun_tandon.id_remun_detil',$detil->id)
                      ->selectRaw('SUM(dt_remun_tandon.masuk) as masuk,
                                   SUM(dt_remun_tandon.keluar) as keluar')
                      ->first();

      return view('remunerasi_detil',compact('detil','rincian','total','tandon','total_tandon','jasa_real'));
    }    

    public function remunerasi_detil_cetak($id){
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

                                 dt_remun_detil.r_total_indek,
                                 dt_remun_detil.r_medis,
                                 dt_remun_detil.r_jasa_pelayanan,
                                 dt_remun_detil.medis_dokter,
                                 dt_remun_detil.nominal_pajak,
                                 dt_remun_detil.sisa,
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
                    ->where('dt_remun_detil.id',Crypt::decrypt($id))
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
                                     DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d %b %Y") as tanggal,
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

        $tandon     = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$detil->id_remun)
                      ->where('dt_remun_tandon.id_remun_detil',$detil->id)
                      ->orderby('dt_remun_tandon.id')
                      ->get();

      $total_tandon     = DB::table('dt_remun_tandon')
                      ->where('dt_remun_tandon.id_remun',$detil->id_remun)
                      ->where('dt_remun_tandon.id_remun_detil',$detil->id)
                      ->selectRaw('SUM(dt_remun_tandon.medis_masuk) + SUM(dt_remun_tandon.perawat_masuk) as masuk,
                                   SUM(dt_remun_tandon.medis_keluar) + SUM(dt_remun_tandon.perawat_keluar) as keluar')
                      ->first();

        return view('remunerasi_detil_cetak',compact('detil','rincian','total','tandon','total_tandon'));
    }    

    public function remunerasi_ok($id){
        DB::table('dt_remun')
          ->where('id',Crypt::decrypt($id))
          ->update([
              'waktu_setor' => now(),
              'stat' => 3,
              'petugas_update' => Auth::user()->id,
          ]);

        DB::table('dt_remun_back')
          ->where('id',Crypt::decrypt($id))
          ->update([
              'waktu_setor' => now(),
              'stat' => 3,
              'petugas_update' => Auth::user()->id,
          ]);

        DB::select('CALL remun_backup('.Crypt::decrypt($id).');');

        return redirect()->route('remunerasi_admin');
    }

    public function remunerasi_export($id){
        return Excel::download(new RemunExport(Crypt::decrypt($id)), 'Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function remunerasi_cetak(request $request){
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
        
        $remun  = DB::table('dt_remun')
                    ->where('dt_remun.id',$request->id)
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
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
                                    (dt_remun_detil.r_medis + dt_remun_detil.titipan) as r_jumlah,

                                    dt_remun_detil.r_jasa_pelayanan,
                                    dt_remun_detil.nominal_pajak,
                                    dt_remun_detil.sisa,

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

        return view('remunerasi_cetak',compact('remun','rincian','jumlah','jenis'));
    }

    public function remunerasi_edit_show(request $request){
      $data   = DB::table('dt_remun_detil')
                  ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                  ->where('dt_remun_detil.id',$request->id)
                  ->selectRaw('dt_remun_detil.id,
                               dt_remun_detil.r_medis,
                               CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                  ->first();

      echo json_encode($data);
    }

    public function remunerasi_edit(request $request){
        $cek    = DB::table('dt_remun_detil')->where('id',$request->id)->first();

        if($cek->r_medis <> str_replace(',','',$request->r_medis)){
            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    'rs_medis' => DB::raw('r_medis'),
                    'r_medis' => str_replace(',','',$request->r_medis),
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                               
                                ->where('users.medis','>',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->orwhere('users.id_tenaga_bagian',2)
                                ->where('users.staf',0)
                                ->where('users.jp_admin',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->orwhere('users.id_tenaga_bagian',3)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.r_medis')
                                
                                ->where('users.medis','>',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->orwhere('users.id_tenaga_bagian',2)
                                ->where('users.staf',0)
                                ->where('users.jp_admin',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->orwhere('users.id_tenaga_bagian',3)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->get();

            #Penambahan
            if($data->rs_medis > $data->r_medis){
                $selisih    = $data->rs_medis - $data->r_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_medis' => $rinc->r_medis + ($selisih * ($rinc->r_medis / $total->r_medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->rs_medis < $data->r_medis){
                $selisih    = $data->r_medis - $data->rs_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_medis' => $rinc->r_medis - ($selisih * ($rinc->r_medis / $total->r_medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    'rs_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);

            $medis  = $cek->medis * (str_replace(',','',$request->r_medis) / $cek->r_medis);

            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    's_medis' => DB::raw('medis'),
                    'medis' => $medis,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->selectRaw('SUM(dt_remun_detil.medis) as medis')

                                ->where('users.medis','>',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->orwhere('users.id_tenaga_bagian',2)
                                ->where('users.staf',0)
                                ->where('users.jp_admin',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->orwhere('users.id_tenaga_bagian',3)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)

                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.medis')

                                ->where('users.medis','>',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->orwhere('users.id_tenaga_bagian',2)
                                ->where('users.staf',0)
                                ->where('users.jp_admin',0)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->orwhere('users.id_tenaga_bagian',3)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_medis','>',0)

                                ->get();

            #Penambahan
            if($data->s_medis > $data->medis){
                $selisih    = $data->s_medis - $data->medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'medis' => $rinc->medis + ($selisih * ($rinc->medis / $total->medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->s_medis < $data->medis){
                $selisih    = $data->medis - $data->s_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'medis' => $rinc->medis - ($selisih * ($rinc->medis / $total->medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    's_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        return back();
    }

    public function remunerasi_komulatif(request $request){
        if($request->id_ruang){
            $id_ruang   = $request->id_ruang;
        } else {
            $id_ruang   = '';
        }

        $id_bagian = $request->id_bagian;

        #Conversi
        $lama   = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->selectRaw('dt_remun_detil.id,
                                 dt_remun_detil.r_medis')

                    ->where('dt_remun_detil.id_remun',$request->id_remun)
                    ->where('dt_remun_detil.r_medis','>',0)

                    ->when($id_bagian == 2, function ($query) use ($id_bagian) {
                      return $query->where('users.id_tenaga_bagian',$id_bagian)
                                   ->where('users.staf',0)
                                   ->where('users.jp_admin',0);                                   
                    })

                    ->when($id_bagian == 3, function ($query) use ($id_bagian) {
                      return $query->where('users.id_tenaga_bagian',$id_bagian);
                    })

                    ->when($id_bagian <> 2 && $id_bagian <> 3, function ($query) use ($id_bagian) {
                      return $query->where('users.medis','>',0)                      
                                   ->where('users.id_tenaga_bagian',$id_bagian);
                    })                    
                    
                    ->when($id_ruang, function ($query) use ($id_ruang) {
                        return $query->where('users.id_ruang',$id_ruang);
                    })

                    ->get();

        foreach($lama as $lam){
            DB::table('dt_remun_detil')
                ->where('id',$lam->id)
                ->update([
                    'rs_medis' => $lam->r_medis,
                    'r_medis' => $lam->r_medis * ($request->persen/100),
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        $total          = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

        $rincian        = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('dt_remun_detil.id,
                                         dt_remun_detil.r_medis')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->get();

        if($request->persen < 100){
            $selisih    = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.rs_medis) - SUM(dt_remun_detil.r_medis) as total')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_medis' => $rinc->r_medis + ($selisih->total * ($rinc->r_medis / $total->r_medis)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }
        }

        if($request->persen > 100){
            $selisih    = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.r_medis) - SUM(dt_remun_detil.rs_medis) as total')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'r_medis' => $rinc->r_medis - ($selisih->total * ($rinc->r_medis / $total->r_medis)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }
        }

        DB::table('dt_remun_detil')
            ->where('id_remun',$request->id_remun)
            ->update([
                'rs_medis' => 0,
                'petugas_update' => Auth::user()->id,
            ]);


        #Perhitungan
        $lama   = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->selectRaw('dt_remun_detil.id,
                                 dt_remun_detil.medis')

                    ->where('dt_remun_detil.id_remun',$request->id_remun)
                    ->where('dt_remun_detil.r_medis','>',0)

                    ->when($id_bagian == 2, function ($query) use ($id_bagian) {
                      return $query->where('users.id_tenaga_bagian',$id_bagian)
                                   ->where('users.staf',0)
                                   ->where('users.jp_admin',0);                                   
                    })

                    ->when($id_bagian == 3, function ($query) use ($id_bagian) {
                      return $query->where('users.id_tenaga_bagian',$id_bagian);
                    })

                    ->when($id_bagian <> 2 && $id_bagian <> 3, function ($query) use ($id_bagian) {
                      return $query->where('users.medis','>',0)                      
                                   ->where('users.id_tenaga_bagian',$id_bagian);
                    })                    
                    
                    ->when($id_ruang, function ($query) use ($id_ruang) {
                        return $query->where('users.id_ruang',$id_ruang);
                    })

                    ->get();

        foreach($lama as $lam){
            DB::table('dt_remun_detil')
                ->where('id',$lam->id)
                ->update([
                    'rs_medis' => $lam->medis,
                    'medis' => $lam->medis * ($request->persen/100),
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        $total          = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.medis) as medis')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

        $rincian        = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('dt_remun_detil.id,
                                         dt_remun_detil.medis')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->get();

        if($request->persen < 100){
            $selisih    = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.rs_medis) - SUM(dt_remun_detil.medis) as total')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'medis' => $rinc->medis + ($selisih->total * ($rinc->medis / $total->medis)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }
        }

        if($request->persen > 100){
            $selisih    = DB::table('dt_remun_detil')
                            ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                            ->selectRaw('SUM(dt_remun_detil.medis) - SUM(dt_remun_detil.rs_medis) as total')

                            ->where('users.medis','>',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',2)
                            ->where('users.staf',0)
                            ->where('users.jp_admin',0)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->orwhere('users.id_tenaga_bagian',3)
                            ->where('dt_remun_detil.rs_medis','>',0)
                            ->where('dt_remun_detil.id_remun',$request->id_remun)
                            ->where('dt_remun_detil.r_medis','>',0)

                            ->first();

            foreach($rincian as $rinc){
                DB::table('dt_remun_detil')
                    ->where('id',$rinc->id)
                    ->update([
                        'medis' => $rinc->medis - ($selisih->total * ($rinc->medis / $total->medis)),
                        'petugas_update' => Auth::user()->id,
                    ]);
            }
        }

        DB::table('dt_remun_detil')
            ->where('id_remun',$request->id_remun)
            ->update([
                'rs_medis' => 0,
                'petugas_update' => Auth::user()->id,
            ]);        

        return back();
    }

    public function remunerasi_admin_edit_show(request $request){
      $data = DB::table('dt_remun_detil')
                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                ->where('dt_remun_detil.id',$request->id)
                ->selectRaw('dt_remun_detil.id,
                             dt_remun_detil.r_administrasi,
                             CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                             IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                ->first();

      echo json_encode($data);
    }

    public function remunerasi_admin_edit(request $request){
      $cek    = DB::table('dt_remun_detil')->where('id',$request->id)->first();

        if($cek->r_administrasi <> $request->r_administrasi){
            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    'rs_medis' => DB::raw('r_administrasi'),
                    'r_administrasi' => $request->r_administrasi,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.r_administrasi) as r_administrasi')
                                ->where('users.id_ruang',64)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.r_administrasi')
                                ->where('users.id_ruang',64)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->get();

            #Penambahan
            if($data->rs_medis > $data->r_administrasi){
                $selisih    = $data->rs_medis - $data->r_administrasi;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_administrasi' => $rinc->r_administrasi + ($selisih * ($rinc->r_administrasi / $total->r_administrasi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->rs_medis < $data->r_administrasi){
                $selisih    = $data->r_administrasi - $data->rs_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_administrasi' => $rinc->r_administrasi - ($selisih * ($rinc->r_administrasi / $total->r_administrasi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    'rs_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);

            $medis  = $cek->administrasi * ($request->r_administrasi / $cek->r_administrasi);

            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    's_medis' => DB::raw('administrasi'),
                    'administrasi' => $medis,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.administrasi) as administrasi')
                                ->where('users.id_ruang',64)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.administrasi')
                                ->where('users.id_ruang',64)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->get();

            #Penambahan
            if($data->s_medis > $data->administrasi){
                $selisih    = $data->s_medis - $data->administrasi;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'administrasi' => $rinc->administrasi + ($selisih * ($rinc->administrasi / $total->administrasi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->s_medis < $data->administrasi){
                $selisih    = $data->administrasi - $data->s_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'administrasi' => $rinc->administrasi - ($selisih * ($rinc->administrasi / $total->administrasi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    's_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        return back();
    }

    public function remunerasi_staf_edit_show(request $request){
      $data   = DB::table('dt_remun_detil')
                  ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                  ->where('dt_remun_detil.id',$request->id)
                  ->selectRaw('dt_remun_detil.id,
                               dt_remun_detil.r_staf_direksi,
                               CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                               IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                  ->first();

      echo json_encode($data);
    }

    public function remunerasi_staf_edit(request $request){
      $cek    = DB::table('dt_remun_detil')->where('id',$request->id)->first();

        if($cek->r_staf_direksi <> $request->r_staf_direksi){
            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    'rs_medis' => DB::raw('r_staf_direksi'),
                    'r_staf_direksi' => $request->r_staf_direksi,
                    'r_staf_direksi_lock' => 1,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi')
                                ->where('users.id_ruang',61)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_staf_direksi_lock',0)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.r_staf_direksi')
                                ->where('users.id_ruang',61)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_staf_direksi_lock',0)
                                ->get();

            #Penambahan
            if($data->rs_medis > $data->r_staf_direksi){
                $selisih    = $data->rs_medis - $data->r_staf_direksi;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_staf_direksi' => $rinc->r_staf_direksi + ($selisih * ($rinc->r_staf_direksi / $total->r_staf_direksi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->rs_medis < $data->r_staf_direksi){
                $selisih    = $data->r_staf_direksi - $data->rs_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_staf_direksi' => $rinc->r_staf_direksi - ($selisih * ($rinc->r_staf_direksi / $total->r_staf_direksi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    'rs_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);

            $medis  = $cek->staf_direksi * ($request->r_staf_direksi / $cek->r_staf_direksi);

            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    's_medis' => DB::raw('staf_direksi'),
                    'staf_direksi' => $medis,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.staf_direksi) as staf_direksi')
                                ->where('users.id_ruang',61)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_staf_direksi_lock',0)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.staf_direksi')
                                ->where('users.id_ruang',61)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->where('dt_remun_detil.r_staf_direksi_lock',0)
                                ->get();

            #Penambahan
            if($data->s_medis > $data->staf_direksi){
                $selisih    = $data->s_medis - $data->staf_direksi;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'staf_direksi' => $rinc->staf_direksi + ($selisih * ($rinc->staf_direksi / $total->staf_direksi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            #Pengurangan
            if($data->s_medis < $data->staf_direksi){
                $selisih    = $data->staf_direksi - $data->s_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'staf_direksi' => $rinc->staf_direksi - ($selisih * ($rinc->staf_direksi / $total->staf_direksi)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    's_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);
        }

        return back();
    }

    public function remunerasi_medis_edit_show(request $request){
      $data   = DB::table('dt_remun_detil')
                  ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                  ->where('dt_remun_detil.id',$request->id)
                  ->selectRaw('dt_remun_detil.id,
                               dt_remun_detil.r_medis,
                               CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                  ->first();

      echo json_encode($data);
    }

    public function remunerasi_medis_edit(request $request){        
        $cek    = DB::table('dt_remun_detil')->where('id',$request->id)->first();

        if($cek->r_medis <> str_replace(',','',$request->r_medis)) {
            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    'rs_medis' => DB::raw('r_medis'),
                    'r_medis' => str_replace(',','',$request->r_medis),
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.r_medis) as r_medis')
                                ->where('users.id_tenaga_bagian',1)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.r_medis')
                                ->where('users.id_tenaga_bagian',1)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->get();

            #Penambahan
            if($data->rs_medis > $data->r_medis){
                $selisih    = $data->rs_medis - $data->r_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_medis' => $rinc->r_medis + ($selisih * ($rinc->r_medis / $total->r_medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }
            }

            #Pengurangan
            if($data->rs_medis < $data->r_medis){
                $selisih    = $data->r_medis - $data->rs_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'r_medis' => $rinc->r_medis - ($selisih * ($rinc->r_medis / $total->r_medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    'rs_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);

            $medis  = $cek->medis * (str_replace(',','',$request->r_medis) / $cek->r_medis);

            DB::table('dt_remun_detil')
                ->where('id',$request->id)
                ->update([
                    's_medis' => DB::raw('medis'),
                    'medis' => $medis,
                    'petugas_update' => Auth::user()->id,
                ]);

            $data           = DB::table('dt_remun_detil')
                                ->where('id',$request->id)
                                ->first();

            $total          = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('SUM(dt_remun_detil.medis) as medis')
                                ->where('users.id_tenaga_bagian',1)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->first();

            $rincian        = DB::table('dt_remun_detil')
                                ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                                ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                                ->selectRaw('dt_remun_detil.id,
                                             dt_remun_detil.medis')
                                ->where('users.id_tenaga_bagian',1)
                                ->where('dt_remun_detil.id','<>',$data->id)
                                ->where('dt_remun_detil.id_remun',$data->id_remun)
                                ->get();

            #Penambahan
            if($data->s_medis > $data->medis){
                $selisih    = $data->s_medis - $data->medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'medis' => $rinc->medis + ($selisih * ($rinc->medis / $total->medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }
            }

            #Pengurangan
            if($data->s_medis < $data->medis){
                $selisih    = $data->medis - $data->s_medis;

                foreach($rincian as $rinc){
                    DB::table('dt_remun_detil')
                        ->where('id',$rinc->id)
                        ->update([
                            'medis' => $rinc->medis - ($selisih * ($rinc->medis / $total->medis)),
                            'petugas_update' => Auth::user()->id,
                        ]);
                }            
            }

            DB::table('dt_remun_detil')
                ->where('dt_remun_detil.id_remun',$data->id_remun)
                ->update([
                    's_medis' => 0,
                    'petugas_update' => Auth::user()->id,
                ]);
        }       
        
        return back();
    }

    public function remunerasi_rincian_export($id){
        return Excel::download(new RemunRincian(Crypt::decrypt($id)), 'Rincian Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function jasa_remun(){
        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_pasien_jenis','dt_remun.id_pasien_jenis','=','dt_pasien_jenis.id')
                    ->where('dt_remun.stat',6)
                    ->where('dt_remun.hapus',0)
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal,"%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as akhir,
                                 dt_remun.r_jp,
                                 dt_pasien_jenis.jenis')
                    ->orderby('dt_remun.id','desc')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.jasa_remun',compact('remun'));
        } else {
            return view('jasa_remun',compact('remun'));
        }
    }

    public function jasa_remun_rincian($id){
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

                                 dt_remun_detil.r_total_indek,
                                 dt_remun_detil.r_medis,
                                 dt_remun_detil.r_jasa_pelayanan,
                                 dt_remun_detil.medis_dokter,
                                 dt_remun_detil.nominal_pajak,
                                 dt_remun_detil.sisa,

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
                    ->where('dt_remun_detil.id_remun',Crypt::decrypt($id))
                    ->where('dt_remun_detil.id_karyawan',Auth::user()->id)
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
                                     DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d %b %Y") as tanggal,
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
        if($detil->r_medis > 0){
        $tot   = DB::table('dt_pasien_layanan_jasa')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('SUM(dt_pasien_layanan_jasa.medis_perawat)+
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi)+
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1)+
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2)+
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen)+
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler)+
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker)+
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker)+
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi)+
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran)+
                                   SUM(dt_pasien_layanan_jasa.medis_fisio) as total')
                      ->first();

        $total     = DB::table('dt_pasien_layanan_jasa')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('SUM(dt_pasien_layanan_jasa.medis_perawat) as medis_perawat,
                                   SUM(dt_pasien_layanan_jasa.medis_perawat)*('.$detil->r_medis.'/'.$tot->total.') as jasa_perawat,
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi) as medis_pen_anastesi,
                                   SUM(dt_pasien_layanan_jasa.medis_pen_anastesi)*('.$detil->r_medis.'/'.$tot->total.') as jasa_pen_anastesi,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1) as medis_per_asisten_1,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_1)*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_1,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2) as medis_per_asisten_2,
                                   SUM(dt_pasien_layanan_jasa.medis_per_asisten_2)*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_2,
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen) as medis_instrumen,
                                   SUM(dt_pasien_layanan_jasa.medis_instrumen)*('.$detil->r_medis.'/'.$tot->total.') as jasa_instrumen,
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler) as medis_sirkuler,
                                   SUM(dt_pasien_layanan_jasa.medis_sirkuler)*('.$detil->r_medis.'/'.$tot->total.') as jasa_sirkuler,
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker) as medis_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_apoteker)*('.$detil->r_medis.'/'.$tot->total.') as jasa_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker) as medis_ass_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_ass_apoteker)*('.$detil->r_medis.'/'.$tot->total.') as jasa_ass_apoteker,
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi) as medis_admin_farmasi,
                                   SUM(dt_pasien_layanan_jasa.medis_admin_farmasi)*('.$detil->r_medis.'/'.$tot->total.') as jasa_admin_farmasi,
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran) as medis_pemulasaran,
                                   SUM(dt_pasien_layanan_jasa.medis_pemulasaran)*('.$detil->r_medis.'/'.$tot->total.') as jasa_pemulasaran,
                                   SUM(dt_pasien_layanan_jasa.medis_fisio) as medis_fisio,
                                   SUM(dt_pasien_layanan_jasa.medis_fisio)*('.$detil->r_medis.'/'.$tot->total.') as jasa_fisio')
                      ->first();

        $rincian  = DB::table('dt_pasien_layanan_jasa')
                      ->leftjoin('dt_ruang','dt_pasien_layanan_jasa.id_ruang_sub','=','dt_ruang.id')
                      ->where('dt_pasien_layanan_jasa.id_remun',$detil->id_remun)
                      ->where('dt_pasien_layanan_jasa.id_karyawan',$detil->id_karyawan)
                      ->selectRaw('dt_ruang.ruang,
                                   dt_pasien_layanan_jasa.medis_perawat,
                                   dt_pasien_layanan_jasa.medis_perawat*('.$detil->r_medis.'/'.$tot->total.') as jasa_perawat,
                                   dt_pasien_layanan_jasa.medis_pen_anastesi,
                                   dt_pasien_layanan_jasa.medis_pen_anastesi*('.$detil->r_medis.'/'.$tot->total.') as jasa_pen_anastesi,
                                   dt_pasien_layanan_jasa.medis_per_asisten_1,
                                   dt_pasien_layanan_jasa.medis_per_asisten_1*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_1,
                                   dt_pasien_layanan_jasa.medis_per_asisten_2,
                                   dt_pasien_layanan_jasa.medis_per_asisten_2*('.$detil->r_medis.'/'.$tot->total.') as jasa_per_asisten_2,
                                   dt_pasien_layanan_jasa.medis_instrumen,
                                   dt_pasien_layanan_jasa.medis_instrumen*('.$detil->r_medis.'/'.$tot->total.') as jasa_instrumen,
                                   dt_pasien_layanan_jasa.medis_sirkuler,
                                   dt_pasien_layanan_jasa.medis_sirkuler*('.$detil->r_medis.'/'.$tot->total.') as jasa_sirkuler,
                                   dt_pasien_layanan_jasa.medis_apoteker,
                                   dt_pasien_layanan_jasa.medis_apoteker*('.$detil->r_medis.'/'.$tot->total.') as jasa_apoteker,
                                   dt_pasien_layanan_jasa.medis_ass_apoteker,
                                   dt_pasien_layanan_jasa.medis_ass_apoteker*('.$detil->r_medis.'/'.$tot->total.') as jasa_ass_apoteker,
                                   dt_pasien_layanan_jasa.medis_admin_farmasi,
                                   dt_pasien_layanan_jasa.medis_admin_farmasi*('.$detil->r_medis.'/'.$tot->total.') as jasa_admin_farmasi,
                                   dt_pasien_layanan_jasa.medis_pemulasaran,
                                   dt_pasien_layanan_jasa.medis_pemulasaran*('.$detil->r_medis.'/'.$tot->total.') as jasa_pemulasaran,
                                   dt_pasien_layanan_jasa.medis_fisio,
                                   dt_pasien_layanan_jasa.medis_fisio*('.$detil->r_medis.'/'.$tot->total.') as jasa_fisio')
                      ->get();

        $jasa_real = '';
      } else {
        $total  = '';
        $rincian = '';
        $jasa_real = '';
      }
      }


        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.jasa_remun_rincian',compact('detil','rincian','total','jasa_real'));
        } else {            
            return view('jasa_remun_rincian',compact('detil','rincian','total','jasa_real'));
        }
    }

    public function jasa_remun_cetak($id){
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

                                 dt_remun_detil.r_total_indek,
                                 dt_remun_detil.r_medis,
                                 dt_remun_detil.r_jasa_pelayanan,
                                 dt_remun_detil.medis_dokter,
                                 dt_remun_detil.nominal_pajak,
                                 dt_remun_detil.sisa,

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
                    ->where('dt_remun_detil.id',Crypt::decrypt($id))
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
                                     DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d %b %Y") as tanggal,
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

        return view('jasa_remun_cetak',compact('detil','rincian','total','jasa_real'));
    }    

    public function remunerasi_cetak_jaspel($id){
        $remun  = DB::table('dt_remun')
                    ->where('id',Crypt::decrypt($id))
                    ->selectRaw('dt_remun.id,
                                 dt_remun.id_bpjs,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir')
                    ->first();

        $detil  = DB::table('dt_remun_detil')
                    ->leftjoin('users','dt_remun_detil.id_karyawan','=','users.id')
                    ->leftjoin('users_status','users.id_status','=','users_status.id')
                    ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                    ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                    ->selectRaw('dt_remun_detil.id,
                                 dt_remun_detil.id_remun,
                                 users_tenaga_bagian.urut_bagian,
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                 IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                 users_status.status,
                                 dt_remun_detil.score_real as indek,
                                 UPPER(users.golongan) as golongan,
                                 dt_remun_detil.r_pos_remun, 
                                 dt_remun_detil.r_indek, 
                                 dt_remun_detil.r_insentif_perawat, 
                                 dt_remun_detil.r_direksi, 
                                 dt_remun_detil.r_staf_direksi,
                                 dt_remun_detil.r_administrasi, 

                                 dt_remun_detil.titipan + dt_remun_detil.r_medis as r_medis,

                                 dt_remun_detil.tpp, 
                                 
                                 (dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) AS jasa,

                                 dt_remun_detil.pajak,

                                 ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) * dt_remun_detil.pajak) / 100) AS nom_pajak,
                                 
                                 (dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) - ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) * dt_remun_detil.pajak) / 100) AS total,
                                 dt_ruang.ruang,
                                 users.npwp,
                                 users.rekening,
                                 users.bank')
                    ->where('dt_remun_detil.id_remun',$remun->id)
                    ->orderby('users_tenaga_bagian.urut_bagian')
                    ->orderby('dt_ruang.ruang')
                    ->orderby('users_status.status')
                    ->orderby('users.nama')
                    ->get();

        $total  = DB::table('dt_remun_detil')
                    ->selectRaw('SUM(dt_remun_detil.r_indek) as r_indek,
                                 SUM(dt_remun_detil.r_insentif_perawat) as r_insentif_perawat, 
                                 SUM(dt_remun_detil.r_direksi) as r_direksi, 
                                 SUM(dt_remun_detil.r_staf_direksi) as r_staf_direksi,
                                 SUM(dt_remun_detil.r_administrasi) as r_administrasi, 
                                 SUM(dt_remun_detil.r_medis + dt_remun_detil.titipan) as r_medis,
                                 SUM(dt_remun_detil.tpp) as tpp, 
                                 
                                 SUM((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan)) AS jasa,
                                 
                                 SUM(ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) * dt_remun_detil.pajak) / 100)) AS nom_pajak,

                                 SUM((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) - ROUND(((dt_remun_detil.tpp + dt_remun_detil.r_medis + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) * dt_remun_detil.pajak) / 100)) AS total')
                    
                    ->where('dt_remun_detil.id_remun',$remun->id)
                    ->first();

        $param  = DB::table('parameter')
                    ->selectRaw('(SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as ketua,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as nip_ketua,
                                 
                                 (SELECT 
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as direktur,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as nip_direktur,

                                 (SELECT 
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_bendahara) as bendahara,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_bendahara) as nip_bendahara,

                                 (SELECT 
                                 CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_pelaksana) as pelaksana,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_pelaksana) as nip_pelaksana')
                    ->first();

        return view('remunerasi_cetak_jaspel',compact('remun','detil','total','param'));
    }

    public function remunerasi_export_jaspel($id){
        return Excel::download(new RemunPembayaran(Crypt::decrypt($id)), 'Pembayaran Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function remunerasi_data(){
      $agent = new Agent();
        
      if ($agent->isMobile()) {
        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,     
                                 TIMEDIFF(dt_remun.selesai,dt_remun.mulai) as waktu,
                                (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                 FROM users
                                 WHERE users.id = dt_remun.petugas_create) as petugas,
                                 dt_remun.a_jp,
                                 dt_remun.stat,
                                 dt_remun_status.status,
                                 IF(dt_remun.id_bpjs IS NOT NULL,"PASIEN BPJS","PASIEN UMUM") as jkn')
                    ->where('dt_remun.stat','>',1)
                    ->where('dt_remun.hapus',0)
                    ->orderby('dt_remun.id','desc')
                    ->paginate(10);

        return view('mobile.remunerasi_data',compact('remun'));
      } else {
        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->selectRaw('dt_remun.id,
                                 DATE_FORMAT(dt_remun.tanggal, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,                                 
                                 TIMEDIFF(dt_remun.selesai,dt_remun.mulai) as waktu,
                                (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                 FROM users
                                 WHERE users.id = dt_remun.petugas_create) as petugas,
                                 dt_remun.a_jp,
                                 dt_remun.stat,
                                 dt_remun_status.status,
                                 IF(dt_remun.id_bpjs IS NOT NULL,"PASIEN BPJS","PASIEN UMUM") as jkn')
                    ->where('dt_remun.stat','>',1)
                    ->where('dt_remun.hapus',0)
                    ->orderby('dt_remun.id','desc')
                    ->get();

        return view('remunerasi_data',compact('remun'));
      }
    }

    public function remunerasi_data_hapus($id){
        DB::table('dt_remun')
            ->where('id',Crypt::decrypt($id))
            ->update([
              'hapus' => 1,
              'petugas_update' => Auth::user()->id,
            ]);

        return back();
    }

    public function remunerasi_data_detil(request $request){
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
                      ->where('hapus',0)
                      ->orderby('urut')
                      ->get();

        $bpjs   = DB::table('dt_claim_bpjs_stat')
                    ->where('stat',1)
                    ->where('hapus',1)
                    ->selectRaw('dt_claim_bpjs_stat.id,
                                 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as dari,
                                 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as sampai,
                                 (SELECT SUM(dt_claim_bpjs.claim_jalan+dt_claim_bpjs.claim_inap)
                                  FROM dt_claim_bpjs
                                  WHERE dt_claim_bpjs.id_stat = dt_claim_bpjs_stat.id) as claim')
                    ->get();      

        

        $remun  = DB::table('dt_remun')
                    ->leftjoin('dt_remun_status','dt_remun.stat','=','dt_remun_status.id')
                    ->where('dt_remun.id',Crypt::decrypt($request->id))
                    ->selectRaw('dt_remun.id,
                                 dt_remun.tanggal,
                                 dt_remun_status.status,
                                 DATE_FORMAT(dt_remun.tanggal,"%d %M %Y") as tgl,
                                 (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
                                 dt_remun.awal,
                                 dt_remun.akhir,
                                 DATE_FORMAT(dt_remun.awal,"%d %M %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun.akhir,"%d %M %Y") as tgl_akhir,
                                 DATE_FORMAT(dt_remun.jasa_awal,"%d %M %Y") as jasa_awal,
                                 DATE_FORMAT(dt_remun.jasa_akhir,"%d %M %Y") as jasa_akhir,
                                 dt_remun.id_bpjs,
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
        } else {
            $rincian = '';
            $jumlah = '';
        }

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_data_detil',compact('bpjs','remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        } else {
            return view('remunerasi_data_detil',compact('bpjs','remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        }
    }

    public function remunerasi_ori(){
        $remun  = DB::table('dt_remun_back')
                    ->leftjoin('dt_remun_status','dt_remun_back.stat','=','dt_remun_status.id')
                    ->selectRaw('dt_remun_back.id,
                                 DATE_FORMAT(dt_remun_back.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun_back.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun_back.akhir, "%d %M %Y") as akhir,                                 
                                 dt_remun_back.a_jp,
                                 dt_remun_status.status,
                                 IF(dt_remun_back.id_bpjs IS NOT NULL,"PASIEN BPJS","PASIEN UMUM") as jkn')
                    ->orderby('dt_remun_back.id','desc')
                    ->get();

        $agent = new Agent();
        
        if ($agent->isMobile()) {
            return view('mobile.remunerasi_data',compact('remun'));
        } else {
            return view('remunerasi_data',compact('remun'));
        }        
    }

    public function remunerasi_ori_detil(request $request){
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

        $remun  = DB::table('dt_remun_back')
                    ->where('dt_remun_back.id',Crypt::decrypt($request->id))
                    ->selectRaw('dt_remun_back.id,
                                 dt_remun_back.tanggal,
                                 dt_remun_back.awal,
                                 dt_remun_back.akhir,
                                 dt_remun_back.tpp,
                                 dt_remun_back.jp,
                                 dt_remun_back.penghasil,
                                 dt_remun_back.nonpenghasil,
                                 dt_remun_back.medis_perawat,
                                 dt_remun_back.admin,
                                 dt_remun_back.pos_remun,
                                 dt_remun_back.direksi,
                                 dt_remun_back.staf,
                                 dt_remun_back.kel_perawat,
                                 (dt_remun_back.admin+
                                 dt_remun_back.pos_remun+
                                 dt_remun_back.direksi+
                                 dt_remun_back.staf+
                                 dt_remun_back.kel_perawat) as indek,  
                                 dt_remun_back.a_jp,
                                 dt_remun_back.r_jp,
                                 dt_remun_back.r_penghasil,
                                 dt_remun_back.r_nonpenghasil,
                                 dt_remun_back.r_medis_perawat,
                                 dt_remun_back.r_admin,
                                 dt_remun_back.r_pos_remun,
                                 dt_remun_back.r_direksi,
                                 dt_remun_back.r_staf,
                                 dt_remun_back.r_kel_perawat,
                                 (dt_remun_back.r_admin+
                                 dt_remun_back.r_pos_remun+
                                 dt_remun_back.r_direksi+
                                 dt_remun_back.r_staf+
                                 dt_remun_back.r_kel_perawat) as r_indek,
                                 dt_remun_back.stat')
                    ->first();

        if($remun){
        $rincian    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil_back.id,                                    
                                    dt_remun_detil_back.score,
                                    dt_remun_detil_back.tpp,
                                    dt_remun_detil_back.pajak,
                                    dt_remun_detil_back.pos_remun,
                                    dt_remun_detil_back.insentif_perawat,
                                    dt_remun_detil_back.direksi,
                                    dt_remun_detil_back.staf_direksi,
                                    dt_remun_detil_back.administrasi,

                                    dt_remun_detil_back.total_indek,

                                    dt_remun_detil_back.medis as medis,
                                    dt_remun_detil_back.jasa_pelayanan,
                                    dt_remun_detil_back.r_pos_remun,
                                    dt_remun_detil_back.r_insentif_perawat,
                                    dt_remun_detil_back.r_direksi,
                                    dt_remun_detil_back.r_staf_direksi,
                                    dt_remun_detil_back.r_administrasi,
                                    dt_remun_detil_back.r_total_indek,
                                    dt_remun_detil_back.r_medis,
                                    dt_remun_detil_back.r_jasa_pelayanan,
                                    users.jabatan,
                                    users_status.status,
                                    CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama,
                                    users.id_tenaga_bagian,
                                    users_tenaga_bagian.kel_perawat,
                                    users_tenaga_bagian.bagian,
                                    dt_ruang.ruang,                                    
                                    users_tenaga_bagian.urut')
                        ->where('dt_remun_detil_back.id_remun',$remun->id)

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

            $jumlah    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil_back.tpp) as tpp,
                                     SUM(dt_remun_detil_back.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil_back.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil_back.direksi) as direksi,
                                     SUM(dt_remun_detil_back.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil_back.administrasi) as administrasi,
                                     SUM(dt_remun_detil_back.total_indek) AS total_indek,
                                     SUM(dt_remun_detil_back.medis) as medis,
                                     SUM(dt_remun_detil_back.jasa_pelayanan) AS jasa_pelayanan,
                                     SUM(dt_remun_detil_back.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil_back.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil_back.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil_back.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil_back.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil_back.r_total_indek) AS r_total_indek,
                                     SUM(dt_remun_detil_back.r_medis) as r_medis,
                                     SUM(dt_remun_detil_back.r_jasa_pelayanan) AS r_jasa_pelayanan')
                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        
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
            return view('mobile.remunerasi_data_detil',compact('bpjs','remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        } else {
            return view('remunerasi_data_detil',compact('bpjs','remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        }
    }

    public function remunerasi_cetak_kwitansi($id){
        $master = DB::table('dt_remun')
                    ->where('id',Crypt::decrypt($id))
                    ->selectRaw('dt_remun.id,
                                 dt_remun.id_bpjs,
                                 DATE_FORMAT(dt_remun.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun.akhir, "%d %M %Y") as akhir,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun.id_pasien_jenis) as jenis,
                                 dt_remun.x_tpp')
                    ->first();

        $param  = DB::table('parameter')->first();

        $remun  = DB::table('dt_remun_detil')
                    ->where('dt_remun_detil.id_remun',$master->id)
                    ->selectRaw('dt_remun_detil.id_remun,
                                (SELECT dt_remun.tanggal FROM dt_remun WHERE dt_remun.id = dt_remun_detil.id_remun) AS tanggal,
  
                                SUM(dt_remun_detil.tpp) as tpp,    

                                ROUND(SUM(dt_remun_detil.tpp * (dt_remun_detil.pajak) /100)) as tpp_pajak,
 
                                SUM(dt_remun_detil.r_medis + dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) as jp,

                                SUM(ROUND((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_staf_direksi + dt_remun_detil.r_administrasi + dt_remun_detil.r_medis + dt_remun_detil.r_direksi + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100)) as jp_pajak,

                                SUM(dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi) as nonpenghasil,

                                ROUND(SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek + dt_remun_detil.r_insentif_perawat + dt_remun_detil.r_direksi + dt_remun_detil.r_staf_direksi) * dt_remun_detil.pajak /100)) as nonpenghasil_pajak,

                                SUM(dt_remun_detil.r_medis + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) as penghasil,

                                ROUND(SUM((dt_remun_detil.r_medis + dt_remun_detil.r_administrasi + dt_remun_detil.titipan) * dt_remun_detil.pajak /100)) as penghasil_pajak,
   
                                SUM(dt_remun_detil.r_direksi) AS direksi,  
                                ROUND(SUM(dt_remun_detil.r_direksi * dt_remun_detil.pajak / 100)) AS direksi_pajak,
  
                                SUM(dt_remun_detil.r_staf_direksi) AS staf_direksi,
                                ROUND(SUM(dt_remun_detil.r_staf_direksi * dt_remun_detil.pajak / 100)) AS staf_pajak,

  
                                SUM(dt_remun_detil.r_pos_remun) AS pos_remun,
                                ROUND(SUM((dt_remun_detil.tpp + dt_remun_detil.r_indek) * dt_remun_detil.pajak / 100)) AS pos_remun_pajak,

                                SUM(dt_remun_detil.r_insentif_perawat) AS insentif_perawat,
                                ROUND(SUM(dt_remun_detil.r_insentif_perawat * dt_remun_detil.pajak / 100)) AS insentif_perawat_pajak,
  
                                SUM(dt_remun_detil.r_administrasi) AS administrasi,
                                ROUND(SUM(dt_remun_detil.r_administrasi * dt_remun_detil.pajak / 100)) AS administrasi_pajak,

                                SUM(dt_remun_detil.r_medis + dt_remun_detil.titipan) AS medis,
                                ROUND(SUM((dt_remun_detil.r_medis + dt_remun_detil.titipan) * dt_remun_detil.pajak / 100)) AS medis_pajak')

                    ->groupby('dt_remun_detil.id_remun')
                    ->first();

        $param  = DB::table('parameter')
                    ->selectRaw('(SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as ketua,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_ketua_tim) as nip_ketua,

                                 parameter.direktur_plt,
                                 
                                 (SELECT 
                                  CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                  IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as direktur,

                                 (SELECT users.nip
                                  FROM users
                                  WHERE users.id = parameter.id_direktur) as nip_direktur')
                    ->first();

        return view('remunerasi_kwitansi',compact('remun','master','param'));
    }

    public function remunerasi_kwitansi_export($id){
        return Excel::download(new RemunKwitansi(Crypt::decrypt($id)), 'Kwitansi Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function edit_jasa_tambahan(request $request){
      $data   = DB::table('dt_remun_detil')
                  ->where('dt_remun_detil.id',$request->id)
                  ->selectRaw('dt_remun_detil.id,
                               dt_remun_detil.id_remun,
                               FORMAT(dt_remun_detil.alokasi_apotik,2) as alokasi_apotik')
                  ->first();

      echo json_encode($data);
    }

    public function remunerasi_backup(){
      if(Auth::user()->id_akses == 1){
        $status = 3;
      } else {
        $status = 0;
      }

      $remun  = DB::table('dt_remun_back')
                    ->selectRaw('dt_remun_back.id,
                                 DATE_FORMAT(dt_remun_back.tanggal, "%W, %d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun_back.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun_back.akhir, "%d %M %Y") as akhir,                                 
                                 dt_remun_back.a_jp,
                                 dt_remun_back.stat,
                                 dt_remun_back.hapus,
                                 IF(dt_remun_back.id_bpjs IS NOT NULL,"PASIEN BPJS","PASIEN UMUM") as jkn')
                    ->where('dt_remun_back.hapus',0)
                    ->where('dt_remun_back.stat',$status)
                    ->orderby('dt_remun_back.id','desc')
                    ->get();

      return view('remunerasi_backup',compact('remun'));
    }

    public function remunerasi_backup_detil(request $request){
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

      if(Auth::user()->id_akses == 1){
        $ke   = 2;
      } else {
        $ke   = 1;
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

      $remun      = DB::table('dt_remun_back')
                      ->selectRaw('dt_remun_back.id,
                                   dt_remun_back.tanggal,
                                   DATE_FORMAT(dt_remun_back.tanggal,"%d %M %Y") as tgl,
                                   dt_remun_back.id_bpjs,
                                   (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun_back.id_pasien_jenis) as jenis,
                                   dt_remun_back.awal,
                                   DATE_FORMAT(dt_remun_back.akhir,"%d %M %Y") as tgl_akhir,
                                   DATE_FORMAT(dt_remun_back.awal,"%d %M %Y") as tgl_awal,
                                   dt_remun_back.akhir,
                                   DATE_FORMAT(dt_remun_back.jasa_awal,"%d %b %Y") as jasa_awal,
                                   DATE_FORMAT(dt_remun_back.jasa_akhir,"%d %b %Y") as jasa_akhir,
                                   dt_remun_back.tpp,
                                   dt_remun_back.jp,
                                   dt_remun_back.penghasil,
                                   dt_remun_back.nonpenghasil,
                                   dt_remun_back.medis_perawat,
                                   dt_remun_back.admin,
                                   dt_remun_back.pos_remun,
                                   dt_remun_back.indek,
                                   dt_remun_back.direksi,
                                   dt_remun_back.staf,
                                   dt_remun_back.kel_perawat,

                                   (dt_remun_back.admin +
                                    dt_remun_back.indek +
                                    dt_remun_back.tpp + 
                                    dt_remun_back.direksi +
                                    dt_remun_back.staf +
                                    IFNULL(dt_remun_back.kel_perawat,0)) as indeks,  

                                   dt_remun_back.a_jp,
                                   dt_remun_back.r_jp,
                                   dt_remun_back.r_penghasil,
                                   dt_remun_back.r_nonpenghasil,
                                   dt_remun_back.r_medis_perawat,
                                   dt_remun_back.r_admin,
                                   dt_remun_back.r_pos_remun,
                                   dt_remun_back.r_indek,
                                   dt_remun_back.r_direksi,
                                   dt_remun_back.r_staf,
                                   dt_remun_back.r_kel_perawat,

                                   (dt_remun_back.r_admin +
                                    dt_remun_back.r_indek +
                                    dt_remun_back.tpp +
                                    dt_remun_back.r_direksi +
                                    dt_remun_back.r_staf +
                                    IFNULL(dt_remun_back.r_kel_perawat,0)) as r_indeks,

                                   dt_remun_back.stat,
                                   TIMEDIFF(dt_remun_back.selesai,dt_remun_back.mulai) as waktu,
                                   dt_remun_back.langkah')
                      ->first();

      $rincian    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil_back.id,                                    
                                    dt_remun_detil_back.score_real as score,
                                    dt_remun_detil_back.tpp,
                                    dt_remun_detil_back.pajak,
                                    dt_remun_detil_back.pos_remun,
                                    dt_remun_detil_back.insentif_perawat,
                                    dt_remun_detil_back.direksi,
                                    dt_remun_detil_back.staf_direksi,
                                    dt_remun_detil_back.administrasi,

                                    dt_remun_detil_back.total_indek,
                                    dt_remun_detil_back.medis as medis,
                                    dt_remun_detil_back.jasa_pelayanan,

                                    dt_remun_detil_back.r_pos_remun,
                                    dt_remun_detil_back.r_indek,
                                    dt_remun_detil_back.r_insentif_perawat,
                                    dt_remun_detil_back.r_direksi,
                                    dt_remun_detil_back.r_staf_direksi,
                                    dt_remun_detil_back.r_administrasi,

                                    dt_remun_detil_back.r_total_indek,
                                    dt_remun_detil_back.r_medis,
                                    dt_remun_detil_back.r_jasa_pelayanan,
                                    dt_remun_detil_back.nominal_pajak,

                                    dt_remun_detil_back.sisa,

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

                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',$ke)

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

            $jumlah    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil_back.tpp) as tpp,
                                     SUM(dt_remun_detil_back.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil_back.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil_back.direksi) as direksi,
                                     SUM(dt_remun_detil_back.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil_back.administrasi) as administrasi,

                                     SUM(dt_remun_detil_back.total_indek) AS total_indek,

                                     SUM(dt_remun_detil_back.medis) as medis,
                                     SUM(dt_remun_detil_back.jasa_pelayanan) AS jasa_pelayanan,
                                     SUM(dt_remun_detil_back.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil_back.r_indek) as r_indek,
                                     SUM(dt_remun_detil_back.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil_back.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil_back.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil_back.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil_back.r_total_indek) AS r_total_indek,

                                     SUM(dt_remun_detil_back.r_medis) as r_medis,

                                     SUM(dt_remun_detil_back.r_jasa_pelayanan) AS r_jasa_pelayanan,

                                     SUM(dt_remun_detil_back.nominal_pajak) as nominal_pajak,

                                     SUM(dt_remun_detil_back.sisa) as sisa')
                        
                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',$ke)
                        
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
        
        return view('remunerasi_backup_detil',compact('remun','rincian','jumlah','jenis','id_status','id_ruang','id_bagian','status','ruang','bagian'));
    }

    public function remunerasi_backup_cetak(request $request){
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

        if(Auth::user()->id_akses == 1){
            $ke   = 2;
        } else {
            $ke   = 1;
        }
        
        $remun  = DB::table('dt_remun_back')
                    ->where('dt_remun_back.id',$request->id)
                    ->selectRaw('dt_remun_back.id,
                                 dt_remun_back.tanggal,
                                 dt_remun_back.awal,
                                 dt_remun_back.akhir,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun_back.id_pasien_jenis) as jenis,
                                 DATE_FORMAT(dt_remun_back.awal,"%d %M %Y") as tgl_awal,
                                 DATE_FORMAT(dt_remun_back.akhir,"%d %M %Y") as tgl_akhir,
                                 dt_remun_back.id_bpjs,
                                 dt_remun_back.tpp,
                                 dt_remun_back.jp,
                                 dt_remun_back.penghasil,
                                 dt_remun_back.nonpenghasil,
                                 dt_remun_back.medis_perawat,
                                 dt_remun_back.admin,
                                 dt_remun_back.pos_remun,
                                 dt_remun_back.indek,
                                 dt_remun_back.direksi,
                                 dt_remun_back.staf,
                                 dt_remun_back.kel_perawat,

                                 (dt_remun_back.admin +
                                 dt_remun_back.indek +
                                 dt_remun_back.tpp + 
                                 dt_remun_back.direksi +
                                 dt_remun_back.staf +
                                 IFNULL(dt_remun_back.kel_perawat,0)) as indeks,  

                                 dt_remun_back.a_jp,
                                 dt_remun_back.r_jp,
                                 dt_remun_back.r_penghasil,
                                 dt_remun_back.r_nonpenghasil,
                                 dt_remun_back.r_medis_perawat,
                                 dt_remun_back.r_admin,
                                 dt_remun_back.r_pos_remun,
                                 dt_remun_back.r_indek,
                                 dt_remun_back.r_direksi,
                                 dt_remun_back.r_staf,
                                 dt_remun_back.r_kel_perawat,

                                 (dt_remun_back.r_admin +
                                 dt_remun_back.r_indek +
                                 dt_remun_back.tpp +
                                 dt_remun_back.r_direksi +
                                 dt_remun_back.r_staf +
                                 IFNULL(dt_remun_back.r_kel_perawat,0)) as r_indeks,

                                 dt_remun_back.stat')
                    ->first();

        $rincian    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil_back.id,                                    
                                    dt_remun_detil_back.score_real as score,
                                    dt_remun_detil_back.tpp,
                                    dt_remun_detil_back.pajak,
                                    dt_remun_detil_back.pos_remun,
                                    dt_remun_detil_back.insentif_perawat,
                                    dt_remun_detil_back.direksi,
                                    dt_remun_detil_back.staf_direksi,
                                    dt_remun_detil_back.administrasi,
                                    dt_remun_detil_back.total_indek,

                                    dt_remun_detil_back.medis as medis,
                                    dt_remun_detil_back.medis + dt_remun_detil_back.titipan as jumlah,

                                    dt_remun_detil_back.jasa_pelayanan,
                                    dt_remun_detil_back.r_pos_remun,
                                    dt_remun_detil_back.r_indek,
                                    dt_remun_detil_back.r_insentif_perawat,
                                    dt_remun_detil_back.r_direksi,
                                    dt_remun_detil_back.r_staf_direksi,
                                    dt_remun_detil_back.r_administrasi,
                                    dt_remun_detil_back.r_total_indek,
                                    dt_remun_detil_back.r_medis,
                                    dt_remun_detil_back.titipan,
                                    dt_remun_detil_back.r_medis + dt_remun_detil_back.titipan as r_jumlah,
                                    dt_remun_detil_back.r_jasa_pelayanan,
                                    dt_remun_detil_back.nominal_pajak,
                                    dt_remun_detil_back.sisa,

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

                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',$ke)

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

            $jumlah    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil_back.tpp) as tpp,
                                     SUM(dt_remun_detil_back.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil_back.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil_back.direksi) as direksi,
                                     SUM(dt_remun_detil_back.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil_back.administrasi) as administrasi,
                                     SUM(dt_remun_detil_back.total_indek) AS total_indek,
                                     SUM(dt_remun_detil_back.medis) as medis,
                                     SUM(dt_remun_detil_back.medis + dt_remun_detil_back.titipan) as jumlah,
                                     SUM(dt_remun_detil_back.jasa_pelayanan) AS jasa_pelayanan,
                                     SUM(dt_remun_detil_back.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil_back.r_indek) as r_indek,
                                     SUM(dt_remun_detil_back.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil_back.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil_back.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil_back.r_administrasi) as r_administrasi,
                                     SUM(dt_remun_detil_back.r_total_indek) AS r_total_indek,
                                     SUM(dt_remun_detil_back.r_medis) as r_medis,
                                     SUM(dt_remun_detil_back.titipan) as titipan,
                                     SUM(dt_remun_detil_back.r_medis + dt_remun_detil_back.titipan) as r_jumlah,
                                     SUM(dt_remun_detil_back.r_jasa_pelayanan) AS r_jasa_pelayanan,
                                     SUM(dt_remun_detil_back.nominal_pajak) as nominal_pajak,
                                     SUM(dt_remun_detil_back.sisa) as sisa')
                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',$ke)
                        
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

        return view('remunerasi_cetak',compact('remun','rincian','jumlah','jenis'));
    }

    public function remunerasi_backup_export($id){
        return Excel::download(new RemunOriginal(Crypt::decrypt($id)), 'Remunerasi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function remunerasi_original(){      
        $remun  = DB::table('dt_remun_back')
                    ->selectRaw('dt_remun_back.id,
                                 DATE_FORMAT(dt_remun_back.tanggal, "%d %M %Y") as tanggal,
                                 DATE_FORMAT(dt_remun_back.awal, "%d %M %Y") as awal,
                                 DATE_FORMAT(dt_remun_back.akhir, "%d %M %Y") as akhir,
                                 dt_remun_back.a_jp,
                                 (SELECT dt_pasien_jenis.jenis
                                  FROM dt_pasien_jenis
                                  WHERE dt_pasien_jenis.id = dt_remun_back.id_pasien_jenis) as jkn')
                    ->where('dt_remun_back.stat','>',2)
                    ->where('dt_remun_back.hapus',0)
                    ->orderby('dt_remun_back.id','desc')
                    ->get();

      $agent = new Agent();

      if ($agent->isMobile()) {
        return view('mobile.remunerasi_original',compact('remun'));
      } else {
        return view('remunerasi_original',compact('remun'));
      }
    }

    public function remunerasi_original_detil(request $request){      
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

        $remun  = DB::table('dt_remun_back')
                    ->where('dt_remun_back.id',Crypt::decrypt($request->id_remun))
                    ->selectRaw('dt_remun_back.id,
                                   dt_remun_back.tanggal,
                                   DATE_FORMAT(dt_remun_back.tanggal,"%d %M %Y") as tgl,
                                   dt_remun_back.id_bpjs,
                                   (SELECT dt_pasien_jenis.jenis 
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_remun_back.id_pasien_jenis) as jenis,
                                   dt_remun_back.awal,
                                   DATE_FORMAT(dt_remun_back.akhir,"%d %M %Y") as tgl_akhir,
                                   DATE_FORMAT(dt_remun_back.awal,"%d %M %Y") as tgl_awal,
                                   dt_remun_back.akhir,
                                   DATE_FORMAT(dt_remun_back.jasa_awal,"%d %b %Y") as jasa_awal,
                                   DATE_FORMAT(dt_remun_back.jasa_akhir,"%d %b %Y") as jasa_akhir,
                                   dt_remun_back.tpp,
                                   dt_remun_back.jp,
                                   dt_remun_back.penghasil,
                                   dt_remun_back.nonpenghasil,
                                   dt_remun_back.medis_perawat,
                                   dt_remun_back.admin,
                                   dt_remun_back.pos_remun,
                                   dt_remun_back.indek,
                                   dt_remun_back.direksi,
                                   dt_remun_back.staf,
                                   dt_remun_back.kel_perawat,

                                   (dt_remun_back.admin +
                                    dt_remun_back.indek +
                                    dt_remun_back.tpp + 
                                    dt_remun_back.direksi +
                                    dt_remun_back.staf +
                                    IFNULL(dt_remun_back.kel_perawat,0)) as indeks,  

                                   dt_remun_back.a_jp,
                                   dt_remun_back.r_jp,
                                   dt_remun_back.r_penghasil,
                                   dt_remun_back.r_nonpenghasil,
                                   dt_remun_back.r_medis_perawat,
                                   dt_remun_back.r_admin,
                                   dt_remun_back.r_pos_remun,
                                   dt_remun_back.r_indek,
                                   dt_remun_back.r_direksi,
                                   dt_remun_back.r_staf,
                                   dt_remun_back.r_kel_perawat,

                                   (dt_remun_back.r_admin +
                                    dt_remun_back.r_indek +
                                    dt_remun_back.tpp +
                                    dt_remun_back.r_direksi +
                                    dt_remun_back.r_staf +
                                    IFNULL(dt_remun_back.r_kel_perawat,0)) as r_indeks,

                                   dt_remun_back.stat,
                                   dt_remun_back.langkah')
                    ->first();        

        $rincian    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->leftjoin('dt_ruang','users.id_ruang','=','dt_ruang.id')
                        ->leftjoin('users_status','users.id_status','=','users_status.id')
                        ->selectRaw('dt_remun_detil_back.id,                                    
                                    dt_remun_detil_back.score,
                                    dt_remun_detil_back.tpp,
                                    dt_remun_detil_back.pajak,
                                    dt_remun_detil_back.pos_remun,
                                    dt_remun_detil_back.insentif_perawat,
                                    dt_remun_detil_back.direksi,
                                    dt_remun_detil_back.staf_direksi,
                                    dt_remun_detil_back.administrasi,

                                    dt_remun_detil_back.total_indek,

                                    dt_remun_detil_back.medis as medis,
                                    dt_remun_detil_back.medis + dt_remun_detil_back.titipan as jumlah,

                                    dt_remun_detil_back.jasa_pelayanan,

                                    dt_remun_detil_back.r_pos_remun,
                                    dt_remun_detil_back.r_indek,
                                    dt_remun_detil_back.r_insentif_perawat,
                                    dt_remun_detil_back.r_direksi,
                                    dt_remun_detil_back.r_staf_direksi,
                                    dt_remun_detil_back.r_administrasi,

                                    dt_remun_detil_back.r_total_indek,
                                    
                                    dt_remun_detil_back.r_medis,
                                    dt_remun_detil_back.titipan,
                                    dt_remun_detil_back.r_medis + dt_remun_detil_back.titipan as r_jumlah,

                                    dt_remun_detil_back.r_jasa_pelayanan,

                                    dt_remun_detil_back.nominal_pajak,

                                    dt_remun_detil_back.sisa,

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
                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',1)

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

            $jumlah    = DB::table('dt_remun_detil_back')
                        ->leftjoin('users','dt_remun_detil_back.id_karyawan','=','users.id')
                        ->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        ->selectRaw('SUM(dt_remun_detil_back.tpp) as tpp,
                                     SUM(dt_remun_detil_back.pos_remun) as pos_remun,
                                     SUM(dt_remun_detil_back.insentif_perawat) as insentif_perawat,
                                     SUM(dt_remun_detil_back.direksi) as direksi,
                                     SUM(dt_remun_detil_back.staf_direksi) as staf_direksi,
                                     SUM(dt_remun_detil_back.administrasi) as administrasi,

                                     SUM(dt_remun_detil_back.total_indek) AS total_indek,

                                     SUM(dt_remun_detil_back.medis) as medis,
                                     SUM(dt_remun_detil_back.medis + dt_remun_detil_back.titipan) as jumlah,

                                     SUM(dt_remun_detil_back.jasa_pelayanan) AS jasa_pelayanan,
                                     SUM(dt_remun_detil_back.r_pos_remun) as r_pos_remun,
                                     SUM(dt_remun_detil_back.r_indek) as r_indek,
                                     SUM(dt_remun_detil_back.r_insentif_perawat) as r_insentif_perawat,
                                     SUM(dt_remun_detil_back.r_direksi) as r_direksi,
                                     SUM(dt_remun_detil_back.r_staf_direksi) as r_staf_direksi,
                                     SUM(dt_remun_detil_back.r_administrasi) as r_administrasi,

                                     SUM(dt_remun_detil_back.r_total_indek) AS r_total_indek,

                                     SUM(dt_remun_detil_back.r_medis) as r_medis,
                                     SUM(dt_remun_detil_back.titipan) as titipan,
                                     SUM(dt_remun_detil_back.r_medis + dt_remun_detil_back.titipan) as r_jumlah,

                                     SUM(dt_remun_detil_back.r_jasa_pelayanan) AS r_jasa_pelayanan,

                                     SUM(dt_remun_detil_back.nominal_pajak) as nominal_pajak,

                                    SUM(dt_remun_detil_back.sisa) as sisa')
                        ->where('dt_remun_detil_back.id_remun',$remun->id)
                        ->where('dt_remun_detil_back.ke',1)
                        
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
            return view('mobile.remunerasi_original_detil',compact('bpjs','remun','rincian','jumlah','cek','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        } else {
            return view('remunerasi_original_detil',compact('remun','rincian','jumlah','jenis','status','id_status','id_ruang','ruang','bagian','id_bagian'));
        }
      }

      public function remunerasi_rincian(request $request){
        $remun    = $request->id;

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

        if($request->id_jenis){
          $id_jenis = $request->id_jenis;
        } else {
          $id_jenis = '';
        }

        if($request->id_rawat){
          $id_rawat = $request->id_rawat;
        } else {
          $id_rawat = '';
        }

        if($request->id_ruang){
          $id_ruang = $request->id_ruang;
        } else {
          $id_ruang = '';
        }

        if($request->id_ruang_sub){
          $id_ruang_sub = $request->id_ruang_sub;
        } else {
          $id_ruang_sub = '';
        }

        if($request->id_dpjp){
          $id_dpjp = $request->id_dpjp;
        } else {
          $id_dpjp = '';
        }

        $jenis    = DB::table('dt_pasien_jenis')->get();
        $ruang    = DB::table('dt_ruang')->get();
        $dpjp     = DB::table('users')
                      ->where('users.id_tenaga',1)
                      ->selectRaw('users.id,
                                   CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                      ->orderby('users.nama')
                      ->get();

        $rincian  = DB::table('dt_pasien_layanan_remun')
                      ->leftjoin('dt_pasien','dt_pasien_layanan_remun.id_pasien','=','dt_pasien.id')
                      ->where('dt_pasien_layanan_remun.id_remun',$remun)
                      ->selectRaw('dt_pasien_layanan_remun.id,
                                   dt_pasien.no_mr,
                                   CONCAT(YEAR(dt_pasien.tgl_data),LPAD(dt_pasien.register,7,0),LPAD(MONTH(dt_pasien.tgl_data),2,0)) as register,
                                   DATE_FORMAT(dt_pasien_layanan_remun.waktu,"%d/%m/%Y") as waktu,
                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_petugas) as petugas,
                                   (SELECT dt_pasien.nama
                                    FROM dt_pasien
                                    WHERE dt_pasien.id = dt_pasien_layanan_remun.id_pasien) as pasien,
                                   (SELECT dt_pasien_jenis.jenis
                                    FROM dt_pasien_jenis
                                    WHERE dt_pasien_jenis.id = dt_pasien_layanan_remun.id_pasien_jenis) as jenis,
                                   IF(dt_pasien_layanan_remun.id_pasien_jenis_rawat = 1,"RAWAT JALAN","RAWAT INAP") as rawat,
                                   DATE_FORMAT(dt_pasien.masuk,"%d/%m/%Y") as masuk,
                                   DATE_FORMAT(dt_pasien_layanan_remun.keluar,"%d/%m/%Y") as keluar,
                                   (SELECT dt_ruang.ruang
                                    FROM dt_ruang
                                    WHERE dt_ruang.id = dt_pasien_layanan_remun.id_ruang) as ruang_perawatan,
                                   (SELECT dt_ruang.ruang
                                    FROM dt_ruang
                                    WHERE dt_ruang.id = dt_pasien_layanan_remun.id_ruang_sub) as ruang_tindakan,

                                   (SELECT dt_jasa.jasa
                                    FROM dt_jasa
                                    WHERE dt_jasa.id = dt_pasien_layanan_remun.id_jasa) as jasa,

                                   dt_pasien_layanan_remun.real_tarif,
                                   dt_pasien_layanan_remun.tarif,
                                   dt_pasien_layanan_remun.n_js,
                                   dt_pasien_layanan_remun.js,
                                   dt_pasien_layanan_remun.n_jp,
                                   dt_pasien_layanan_remun.jp,
                                   dt_pasien_layanan_remun.n_profit,
                                   dt_pasien_layanan_remun.profit,
                                   dt_pasien_layanan_remun.n_penghasil,
                                   dt_pasien_layanan_remun.penghasil,
                                   dt_pasien_layanan_remun.n_non_penghasil,
                                   dt_pasien_layanan_remun.non_penghasil,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_dpjp) as dpjp,
                                    (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_dpjp_real) as dpjp_real,

                                   dt_pasien_layanan_remun.n_dpjp,
                                   dt_pasien_layanan_remun.real_jasa_dpjp,
                                   dt_pasien_layanan_remun.jasa_dpjp,
                                   dt_pasien_layanan_remun.jasa_dpjp_diterima,
                                   dt_pasien_layanan_remun.real_jasa_dpjp_ugd,
                                   dt_pasien_layanan_remun.jasa_dpjp_ugd,
                                   dt_pasien_layanan_remun.jasa_dpjp_ugd_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_pengganti) as pengganti,

                                   dt_pasien_layanan_remun.n_pengganti,
                                   dt_pasien_layanan_remun.real_jasa_pengganti,
                                   dt_pasien_layanan_remun.jasa_pengganti,
                                   dt_pasien_layanan_remun.jasa_pengganti_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_operator) as operator,

                                   dt_pasien_layanan_remun.n_operator,
                                   dt_pasien_layanan_remun.real_jasa_operator,
                                   dt_pasien_layanan_remun.jasa_operator,
                                   dt_pasien_layanan_remun.jasa_operator_diterima,
                                   dt_pasien_layanan_remun.jasa_operator_min,
                                   
                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_anastesi) as anastesi,

                                   dt_pasien_layanan_remun.n_anastesi,
                                   dt_pasien_layanan_remun.real_jasa_anastesi,
                                   dt_pasien_layanan_remun.jasa_anastesi,
                                   dt_pasien_layanan_remun.jasa_anastesi_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_pendamping) as pendamping,

                                   dt_pasien_layanan_remun.n_pendamping,
                                   dt_pasien_layanan_remun.real_jasa_pendamping,
                                   dt_pasien_layanan_remun.jasa_pendamping,
                                   dt_pasien_layanan_remun.jasa_pendamping_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_konsul) as konsul,

                                   dt_pasien_layanan_remun.n_konsul,
                                   dt_pasien_layanan_remun.real_jasa_konsul,
                                   dt_pasien_layanan_remun.jasa_konsul,
                                   dt_pasien_layanan_remun.jasa_konsul_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_laborat) as laborat,

                                   dt_pasien_layanan_remun.n_laborat,
                                   dt_pasien_layanan_remun.real_jasa_laborat,
                                   dt_pasien_layanan_remun.jasa_laborat,
                                   dt_pasien_layanan_remun.jasa_laborat_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_tanggung) as tanggung,

                                   dt_pasien_layanan_remun.n_tanggung,
                                   dt_pasien_layanan_remun.real_jasa_tanggung,
                                   dt_pasien_layanan_remun.jasa_tanggung,
                                   dt_pasien_layanan_remun.jasa_tanggung_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_radiologi) as radiologi,

                                   dt_pasien_layanan_remun.n_radiologi,
                                   dt_pasien_layanan_remun.real_jasa_radiologi,
                                   dt_pasien_layanan_remun.jasa_radiologi,
                                   dt_pasien_layanan_remun.jasa_radiologi_diterima,

                                   (SELECT CONCAT(IF(users.gelar_depan IS NOT NULL,CONCAT(users.gelar_depan," "),""),
                                    IF(users.gelar_belakang IS NOT NULL AND users.gelar_belakang <> "",CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama)))
                                    FROM users
                                    WHERE users.id = dt_pasien_layanan_remun.id_rr) as rr,

                                   dt_pasien_layanan_remun.n_rr,
                                   dt_pasien_layanan_remun.real_jasa_rr,
                                   dt_pasien_layanan_remun.jasa_rr,
                                   dt_pasien_layanan_remun.jasa_rr_diterima,

                                   dt_pasien_layanan_remun.n_jp_perawat,
                                   dt_pasien_layanan_remun.jp_perawat,
                                   dt_pasien_layanan_remun.n_pen_anastesi,
                                   dt_pasien_layanan_remun.pen_anastesi,
                                   dt_pasien_layanan_remun.n_per_asisten_1,
                                   dt_pasien_layanan_remun.per_asisten_1,
                                   dt_pasien_layanan_remun.n_per_asisten_2,
                                   dt_pasien_layanan_remun.per_asisten_2,
                                   dt_pasien_layanan_remun.n_instrumen,
                                   dt_pasien_layanan_remun.instrumen,
                                   dt_pasien_layanan_remun.n_sirkuler,
                                   dt_pasien_layanan_remun.sirkuler,
                                   dt_pasien_layanan_remun.n_per_pendamping_1,
                                   dt_pasien_layanan_remun.per_pendamping_1,
                                   dt_pasien_layanan_remun.n_per_pendamping_2,
                                   dt_pasien_layanan_remun.per_pendamping_2,
                                   dt_pasien_layanan_remun.n_apoteker,
                                   dt_pasien_layanan_remun.apoteker,
                                   dt_pasien_layanan_remun.n_ass_apoteker,
                                   dt_pasien_layanan_remun.ass_apoteker,
                                   dt_pasien_layanan_remun.n_admin_farmasi,
                                   dt_pasien_layanan_remun.admin_farmasi,
                                   dt_pasien_layanan_remun.n_administrasi,
                                   dt_pasien_layanan_remun.administrasi,
                                   dt_pasien_layanan_remun.n_pos_remun,
                                   dt_pasien_layanan_remun.pos_remun,
                                   dt_pasien_layanan_remun.n_direksi,
                                   dt_pasien_layanan_remun.direksi,
                                   dt_pasien_layanan_remun.n_staf_direksi,
                                   dt_pasien_layanan_remun.staf_direksi,
                                   dt_pasien_layanan_remun.n_pemulasaran,
                                   dt_pasien_layanan_remun.pemulasaran,
                                   dt_pasien_layanan_remun.n_fisio,
                                   dt_pasien_layanan_remun.fisio')
                      
                      ->when($id_jenis, function ($query) use ($id_jenis) {
                            return $query->where('dt_pasien_layanan_remun.id_pasien_jenis',$id_jenis);
                        })

                      ->when($id_rawat, function ($query) use ($id_rawat) {
                            return $query->where('dt_pasien_layanan_remun.id_pasien_jenis_rawat',$id_rawat);
                        })

                      ->when($id_ruang, function ($query) use ($id_ruang) {
                            return $query->where('dt_pasien_layanan_remun.id_ruang',$id_ruang);
                        })

                      ->when($id_ruang_sub, function ($query) use ($id_ruang_sub) {
                            return $query->where('dt_pasien_layanan_remun.id_ruang_sub',$id_ruang_sub);
                        })

                      ->when($id_dpjp, function ($query) use ($id_dpjp) {
                            return $query->where('dt_pasien_layanan_remun.id_dpjp',$id_dpjp);
                        })

                      ->when($cari, function ($query) use ($cari) {
                            return $query->where('dt_pasien_layanan_remun.nama','LIKE','%'.$cari.'%');
                        })

                      ->paginate($tampil);

        return view('remunerasi_rincian',compact('rincian','remun','jenis','ruang','dpjp','id_jenis','id_ruang','id_ruang_sub','id_dpjp','id_rawat','tampil','cari'));
      }
}
