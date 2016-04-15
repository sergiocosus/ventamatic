<?php

namespace Ventamatic\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Routing\Route;
use Log;
use Ventamatic\Http\Requests;

class UserController extends Controller
{
    public function __construct(Route $route)
    {
        $this->middleware('jwt.auth');
        Log::info(explode('@',$route->getActionName())[1]);
    }

    public function getUser()
    {
        return Auth::user();
    }
}
