<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
class YangnanController extends Controller
{
    public function index(){
    	echo phpinfo();
    }
    public function table(){
    	
    	Redis::set('name',"zhangy");
    	dd(Redis::get('name'));
    	// $data = DB::table('user')->insert();
    	$res = DB::table('p_users')->get();
    	dd($res);
    }
}
