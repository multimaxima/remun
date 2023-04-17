<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DataController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function database(request $request) {
      $tables_in_db = DB::select('SHOW TABLES');
      $db = "Tables_in_".env('DB_DATABASE');
      $tables = [];
      
      foreach($tables_in_db as $table){
        $tables[] = $table->{$db};
      }      
      return view('database',compact('tables'));
    }
}
