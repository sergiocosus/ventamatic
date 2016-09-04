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
        $supplier_categories = SupplierCategory::all();

        return $this->success(compact('supplier_categories'));
    }

    public function getCategory(SupplierCategory $category)
    {
        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $supplier_category = SupplierCategory::create($request->all());

        return $this->success(compact('supplier_category'));
    }

    public function delete(Request $request, SupplierCategory $supplier_category)
    {
        if($supplier_category->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, SupplierCategory $supplier_category)
    {
        $supplier_category->fill($request->all());
        $supplier_category->update();

        return $this->success(compact('supplier_category'));
    }

}