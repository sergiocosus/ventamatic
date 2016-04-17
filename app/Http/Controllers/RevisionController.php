<?php namespace Ventamatic\Http\Controllers;



use Ventamatic\Core\System\Revision;
use Ventamatic\Core\User\User;

class RevisionController extends Controller
{
    public function get(User $user = null)
    {
        return Revision::all();
    }

    public function getUser(User $user)
    {
        return Revision::whereUserId($user->id)->get();
    }
}