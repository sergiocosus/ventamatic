<?php

namespace Ventamatic\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Routing\Route;
use Log;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Core\User\User;
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
        return User::all();
    }
    
    public function getMe()
    {
        return Auth::user();
    }

    public function getUser(User $user)
    {
        return $user;
    }

    public function post(Request $request)
    {
        User::create($request->all());
    }

    public function put(Request $request, User $user)
    {
        $user->fill($request->all());
        $user->save();
    }

    public function delete(Request $request,  User $user)
    {
        $user->delete();
    }
    
    public function putRole(Request $request,  User $user, Role $role)
    {
        $user->attachRole($role);
    }
    
    public function deleteRole(Request $request, User $user, Role $role)
    {
        $user->detachRole($role);
    }
    
    public function postSchedule(Request $request, User $user)
    {
        /* TODO Fill this method*/
    }
    
    public function patchSchedule(Request $request, User $user, Schedule $schedule)
    {
        /* TODO Fill this method*/
    }

    
    
}
