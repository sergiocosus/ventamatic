<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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

    public function put(Request $request, Branch $branch)
    {
        $branch->fill($request->all());
        $branch->save();

        return $this->success(compact('branch'));
    }

    public function getSearch(Request $request)
    {
        $branches = Branch::search($request->get('search'))
            ->get();

        return $this->success(compact('branches'));
    }


}