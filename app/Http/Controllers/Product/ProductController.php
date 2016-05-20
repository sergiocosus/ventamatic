<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function get()
    {
        $products = Product::all();
        return compact('products');
    }

    public function getProduct(Product $product)
    {
        return compact('product');
    }

    public function post(Request $request)
    {
        $product = Product::create($request->all());
        return compact('product');
    }

    public function delete(Product $product)
    {
        if($product->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, Product $product)
    {
        $product->fill($request->all());
        $product->update();
        return compact('product');
    }

}