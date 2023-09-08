<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function unauthorized()
    {
        return view('error.403');
    }
}
