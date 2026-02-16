<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    //how to show package page
    public function index()
    {
        return view('frontend.package.honeymoonPackage');
    }
}
