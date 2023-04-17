<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use DB;

class Pasien implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $ruang, $jenis;

    function __construct($ruang, $jenis) {
        $this->ruang = $ruang;
        $this->jenis = $jenis;
    }

    public function view(): View {
      $jenis  = $this->jenis;
      $ruang  = $this->ruang;

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
                      ->where('dt_pasien.keluar',NULL)

                      ->when($jenis, function ($query) use ($jenis) {
                        return $query->where('dt_pasien.id_jenis',$jenis);
                      })

                      ->when($ruang, function ($query) use ($ruang) {
                        return $query->where('dt_pasien.id_ruang',$ruang);
                      })

                      ->orderby('dt_pasien.nama')
                      ->get();
       
      return view('export.pasien', compact('pasien'));
    }
}
