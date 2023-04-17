<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use View;
use Crypt;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::statement("SET lc_time_names = 'id_ID'");

        $a_param    = DB::table('parameter')->first();
        $a_control  = DB::table('control')->first();

        //$a_dpjp     = DB::table('users')
                        //->leftjoin('users_tenaga_bagian','users.id_tenaga_bagian','=','users_tenaga_bagian.id')
                        //->where('users_tenaga_bagian.medis',1)
                        //->where('users.hapus',0)
                        //->orderby('users.nama')
                        //->selectRaw('users.id,
                                     //users.nama')
                        //->get();

        $a_ruang    = DB::table('dt_ruang')
                        ->where('hapus',0)
                        ->where('inap',1)
                        ->orwhere('hapus',0)
                        ->where('jalan',1)
                        ->orderby('ruang')
                        ->get();       

        view()->share(compact('a_param','a_control','a_ruang'));
    }
}
