<?php

namespace Ventamatic\Http\Controllers\User;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Routing\Route;
use Log;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Core\User\User;
use Ventamatic\Exceptions\PermissionException;
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
        $this->can('user-get');

        $users = User::all();

        return $this->success(compact('users'));
    }

    public function getUser(User $user)
    {
        $this->can('user-get-detail');

        $user->load('roles');

        return $this->success(compact('user'));
    }

    public function post(Request $request)
    {
        $this->can('user-create');

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        if($request->get('roles')) {
            $user->roles()->attach($request->get('roles'));
        }

        return $this->success(compact('user'));
    }

    public function put(Request $request, User $user)
    {
        $this->can('user-edit');

        $user->fill($request->all());
        $user->save();

        if($request->get('roles')) {
            $user->roles()->sync($request->get('roles'));
        }

        return $this->success(compact('user'));
    }

    public function delete(User $user)
    {
        $this->can('user-delete');
        $user->dieIfAdmin();

        if($user->delete()){
            return $this->success();
        }else{
            return $this->error();
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
