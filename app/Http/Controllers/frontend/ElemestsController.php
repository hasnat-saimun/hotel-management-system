<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ElemestsController extends Controller
{
    //view elements page
    public function index()
    {
        return view('frontend.elemests');   
    }
}
