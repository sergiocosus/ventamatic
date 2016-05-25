<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function get()
    {
        $products = Product::with('categories', 'unit', 'brand')->get();
        
        return $this->success(compact('products'));
    }

    public function getProduct(Product $product)
    {
        return $this->success(compact('product'));
    }

    public function post(Request $request)
    {
        $product = Product::create($request->all());
        
        return $this->success(compact('product'));
    }

    public function delete(Product $product)
    {
        if($product->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Product $product)
    {
        $product->fill($request->all());
        $product->update();
        return $this->success(compact('product'));
    }

    public function getSearch(Request $request)
    {
        $products = Product::search($request->get('search'))->get();

        return $this->success(compact('products'));
    }

}