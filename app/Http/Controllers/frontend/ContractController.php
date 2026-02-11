<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    //contract page
    public function contract(){
        return view('frontend.contract');
    }
}
