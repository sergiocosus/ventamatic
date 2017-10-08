<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\BranchService;
use Ventamatic\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * @var BranchService
     */
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->middleware('auth:api');
        $this->branchService = $branchService;
    }

    public function get()
    {
        $branches = Branch::all();
        return $this->success(compact('branches'));
    }

    public function getBranch(Branch $branch)
    {
        $this->canOnBranch('branch-get-detail', $branch);

        return $this->success(compact('branch'));
    }

    public function put(Request $request, Branch $branch)
    {
        $this->canOnBranch('branch-edit', $branch);

        $this->branchService->update($branch, $request);

        return $this->success(compact('branch'));
    }

    public function getSearch(Request $request)
    {
        $branches = Branch::search($request->get('search'), null, true, true)
            ->get();

        return $this->success(compact('branches'));
    }


}