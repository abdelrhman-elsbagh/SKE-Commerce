<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    function index(Request $request)
    {
        return view('front.index');
    }
}
