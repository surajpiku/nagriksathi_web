<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }
}