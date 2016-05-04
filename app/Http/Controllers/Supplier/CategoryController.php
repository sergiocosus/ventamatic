<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\External\SupplierCategory;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function get()
    {
        return SupplierCategory::all();
    }

    public function getCategory(SupplierCategory $supplierCategory)
    {
        return $supplierCategory;
    }

    public function post(Request $request)
    {
        $category = SupplierCategory::create($request->all());
        return compact('category');
    }

    public function delete(Request $request, SupplierCategory $category)
    {
        if($category->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, SupplierCategory $category)
    {
        $category->fill($request->all());
        $category->update();
        return compact('category');
    }

}