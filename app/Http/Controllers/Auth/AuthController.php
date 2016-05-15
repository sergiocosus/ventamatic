<?php

namespace Ventamatic\Http\Controllers\Auth;

use Illuminate\Http\Request;
use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;
use Ventamatic\Core\User\User;
use Ventamatic\Http\Controllers\Controller;

class AuthController extends Controller
{


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function authenticate(Request $request)
    {
        $credentials = [
            'username' => $request->json('username'),
            'password' => $request->json('password')
        ];

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => \Lang::get('auth.failed')], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = \Auth::user();
        return response()->json(compact('token','user'));
    }


}
