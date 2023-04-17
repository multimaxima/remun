<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use DB;
use Auth;
use View;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        View::composer('*', function ($view) {
            if(Auth::user()){
                if(Auth::user()->id_ruang){
                    $c_ruang    = DB::table('dt_ruang')
                                    ->where('id',Auth::user()->id_ruang)
                                    ->first();
                } else {
                    $c_ruang    = '';
                }

                $c_akses    = DB::table('users_akses')->where('id',Auth::user()->id_akses)->first();

                view()->share(compact('c_ruang','c_akses'));
            }            
        });
    }
}
