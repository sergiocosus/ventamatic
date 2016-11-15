<?php

namespace Ventamatic\Http\Controllers\User;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Routing\Route;
use Log;
use Ventamatic\Core\Branch\Branch;
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

        $user->load('roles','branchRoles');

        return $this->success(compact('user'));
    }

    public function getSearch(Request $request)
    {
        $this->can('user-get');

        $users = User::search($request->get('search'))
            ->get();

        return $this->success(compact('users'));
    }

    public function getMe()
    {
        $user = Auth::user();
        $user->setAppends(['permissions', 'branches']);

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

        return $this->success(compact('user'));
    }

    public function delete(User $user)
    {
        $this->can('user-delete');
        $user->dieIfProtecte();

        if($user->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }
    
    public function putRoles(Request $request,  User $user)
    {
        $this->can('user-role-assign');

        $user->roles()->sync($request->get('roles'));

        $roles = $user->roles;

        return $this->success(compact('roles'));
    }

    public function putBranchRoles(Request $request,  User $user)
    {
        $user->dieIfProtecte();

        foreach ($request->get('branch_roles') as $branch_role) {
            $branch = Branch::find($branch_role['branch_id']);

            try{
                \DB::beginTransaction();
                $this->canOnBranch('user-branch-role-assign', $branch);

                $data = [];
                foreach ($branch_role['branch_roles'] as $branch_role_id){
                    $data[$branch_role_id] = ['branch_id' => $branch->id];
                }

                $user->branchRoles()->newPivotStatement()
                    ->whereBranchId($branch->id)->whereUserId($user->id)->delete();

                Log::info($data);
                $user->branchRoles()->attach($data);

                \DB::commit();
            } catch (\Exception $exception){
                Log::error($exception);
            }

        }

        $branch_roles = $user->branchRoles()->get();

        return $this->success(compact('branch_roles'));
    }

}
