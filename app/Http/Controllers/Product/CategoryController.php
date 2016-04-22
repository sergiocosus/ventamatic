<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Category;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function get()
    {
        return Category::all();
    }

    public function getCategory(Category $category)
    {
        return $category;
    }

    public function post(Request $request)
    {
        $category = Category::create($request->all());
        return compact('category');
    }

    public function delete(Request $request, Category $category)
    {
        if($category->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, Category $category)
    {
        $category->fill($request->all());
        $category->update();
        return compact('category');
    }

}