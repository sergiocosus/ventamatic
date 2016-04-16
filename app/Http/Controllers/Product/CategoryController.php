<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Category;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function get(Category $category = null)
    {
        if($category)
        {
            return $category;
        }

        return Category::all();
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Category $category)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Category $category)
    {
        /* TODO Fill this method*/
    }

}