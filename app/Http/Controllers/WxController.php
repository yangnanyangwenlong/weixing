<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WxController extends Controller
{
    public function text1(){
    	echo '<pre>';print_r($_GET);echo '</pre>';
    }
    public function text2(){
    	echo '<pre>';print_r($_POST);echo '</pre>';
    }
}
