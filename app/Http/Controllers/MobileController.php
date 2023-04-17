<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Crypt;
use Toastr;
use PDF;
use Image;
use Hash;
use Auth;

class MobileController extends Controller
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
    public function pengaturan(){
    	return view('mobile.menu.pengaturan');
    }

    public function karyawan(){
    	return view('mobile.menu.karyawan');
    }

    public function layanan(){
    	return view('mobile.menu.layanan');
    }

    public function bpjs(){
    	return view('mobile.menu.bpjs');
    }

    public function remunerasi(){
    	return view('mobile.menu.remunerasi');
    }

    public function informasi(){
        return view('mobile.informasi');
    }

    public function pasien(){
        return view('mobile.menu.pasien');
    }
}
