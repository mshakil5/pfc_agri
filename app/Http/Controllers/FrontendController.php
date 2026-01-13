<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    
    public function index()
    {
        return view('frontend.index');
    }

    public function aboutUs()
    {
        return view('frontend.about');
    }

    public function rAndD()
    {
        return view('frontend.rAndD');
    }

    public function inquire()
    {
        return view('frontend.inquire');
    }





}
