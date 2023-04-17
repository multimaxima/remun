<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ClaimBPJS implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function view(): View {
        $bpjs     	= DB::table('dt_claim_bpjs_stat')
                      	->where('id',$this->id)
                      	->selectRaw('dt_claim_bpjs_stat.id,
                      				 DATE_FORMAT(dt_claim_bpjs_stat.awal, "%d %M %Y") as awal,
                      				 DATE_FORMAT(dt_claim_bpjs_stat.akhir, "%d %M %Y") as akhir')
                      	->first();

      	$detil   	= DB::table('dt_claim_bpjs')
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
                                   IF(users.gelar_belakang IS NOT NULL,CONCAT(UPPER(users.nama),", ",users.gelar_belakang),UPPER(users.nama))) as nama')
                    	->where('dt_claim_bpjs.id_stat',$bpjs->id)
                    	->orderby('users.nama')
                    	->get();

        $tag      	= DB::table('dt_claim_bpjs')
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

      	return view('export.ClaimBPJS',compact('bpjs','detil','tag'));
    }
}
