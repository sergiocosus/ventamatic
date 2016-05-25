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
        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $category = Category::create($request->all());
        
        return $this->success(compact('category'));
    }

    public function delete(Request $request, Category $category)
    {
        if($category->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Category $category)
    {
        $category->fill($request->all());
        $category->update();
        
        return $this->success(compact('category'));
    }

}