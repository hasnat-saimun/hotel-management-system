<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    //view gallery page
    public function index()
    {
        return view('frontend.gallery');    
    }
}
