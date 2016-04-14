<?php

namespace Ventamatic\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Ventamatic\Http\Requests;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function getUser()
    {
        return Auth::user();
    }
}
