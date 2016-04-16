<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function get(Branch $branch = null)
    {
        if($branch)
        {
            return $branch;
        }
        
        return Branch::all();
    }

    public function put(Branch $branch)
    {
        /* TODO Fill this method*/
    }

}