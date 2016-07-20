<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\External\SupplierCategory;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        return SupplierCategory::all();
    }

    public function getCategory(SupplierCategory $category)
    {
        return $category;
    }

    public function post(Request $request)
    {
        $supplierCategory = SupplierCategory::create($request->all());
        return $this->success(compact('supplierCategory'));
    }

    public function delete(Request $request, SupplierCategory $supplierCategory)
    {
        if($supplierCategory->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, SupplierCategory $supplierCategory)
    {
        $supplierCategory->fill($request->all());
        $supplierCategory->update();
        return $this->success(compact('supplierCategory'));
    }

}