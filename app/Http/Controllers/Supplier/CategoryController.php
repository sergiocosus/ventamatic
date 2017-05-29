<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\External\SupplierCategory;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        $this->can('supplier-category-get');

        $query = SupplierCategory::query();
        if ($request->get('deleted')) {
            $this->can('supplier-category-delete');
            $query->withTrashed();
        }
        $supplier_categories = $query->get();

        return $this->success(compact('supplier_categories'));
    }

    public function getCategory(SupplierCategory $category)
    {
        $this->can('supplier-category-get-detail');

        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $this->can('supplier-category-create');

        $supplier_category = SupplierCategory::create($request->all());

        return $this->success(compact('supplier_category'));
    }

    public function delete(SupplierCategory $supplier_category)
    {
        $this->can('supplier-category-delete');

        if($supplier_category->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function patchRestore(SupplierCategory $supplier_category)
    {
        $this->can('supplier-category-delete');

        if($supplier_category->restore()){
            return $this->success(compact('supplier_category'));
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, SupplierCategory $supplier_category)
    {
        $this->can('supplier-category-edit');

        $supplier_category->fill($request->all());
        $supplier_category->update();

        return $this->success(compact('supplier_category'));
    }

}