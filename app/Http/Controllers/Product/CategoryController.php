<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Category;
use Ventamatic\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get()
    {
        $this->can('category-get');

        $categories = Category::all(); 
        
        return $this->success(compact('categories')); 
    }

    public function getCategory(Category $category)
    {
        $this->can('category-get-detail');

        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $this->can('category-create');

        $category = Category::create($request->all());
        
        return $this->success(compact('category'));
    }

    public function delete(Request $request, Category $category)
    {
        $this->can('category-delete');

        if($category->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Category $category)
    {
        $this->can('category-edit');

        $category->fill($request->all());
        $category->update();
        
        return $this->success(compact('category'));
    }

}