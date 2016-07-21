<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        $branches = Branch::all();
        return $this->success(compact('branches'));
    }

    public function getBranch(Branch $branch)
    {
        return $this->success(compact('branch'));
    }

}