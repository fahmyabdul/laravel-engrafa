<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexDetailController extends Controller
{
    //
    public function index(Request $request){
    	return view('index.index-detail');
    }
}
