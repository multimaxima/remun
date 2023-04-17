<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use DB;
use Crypt;

class IndexController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(){
    	$agent = new Agent();
		
		  if ($agent->isMobile()) {
    		return view('mobile.login');
		  } else {
			 return view('auth.login');
		  }
    }

    /*public function reset(){
      DB::table('users')
        ->update([
          'password' => bcrypt('123456'),
        ]);

      return back();
    }*/
}
