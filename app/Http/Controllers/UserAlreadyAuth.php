<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAlreadyAuth extends Controller
{
    ##prove di tommy
    public function register(){
        abort(405, 'Modalità non concessa');
    }
    public function login(){
        abort(405, 'Modalità non concessa');
    }
}
