<?php

namespace Ventamatic\Http\Controllers\User;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Routing\Route;
use Log;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Core\User\User;
use Ventamatic\Http\Controllers\Controller;
use Ventamatic\Http\Requests;

class UserController extends Controller
{
    public function __construct(Route $route)
    {
        $this->middleware('jwt.auth');
        Log::info(explode('@',$route->getActionName())[1]);
    }

    public function get(Request $request)
    {
        $users = User::all();
        return compact('users');
    }

    public function getUser(User $user)
    {
        return compact('user');
    }

    public function post(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return compact('user');
    }

    public function put(Request $request, User $user)
    {
        $user->fill($request->all());
        $user->save();
        return compact('user');
    }

    public function delete(User $user)
    {
        if($user->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }
    
    public function putRole(Request $request,  User $user, Role $role)
    {
        $user->attachRole($role);
    }
    
    public function deleteRole(Request $request, User $user, Role $role)
    {
        $user->detachRole($role);
    }
    
}
