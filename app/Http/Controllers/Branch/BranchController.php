<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function get()
    {
        return Branch::all();
    }

    public function getBranch(Branch $branch)
    {
        return compact('branch');
    }

}