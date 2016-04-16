<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\External\SupplierCategory;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function get(SupplierCategory $category = null)
    {
        if($category)
        {
            return $category;
        }

        return SupplierCategory::all();
    }

    public function post(Request $request)
    {
        /* TODO Fill this method */
    }

    public function delete(Request $request, SupplierCategory $category)
    {
        /* TODO Fill this method */
    }

    public function put(Request $request, SupplierCategory $category)
    {
        /* TODO Fill this method */
    }

}