<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function get()
    {
        return Product::all();
    }

    public function getProduct(Product $product)
    {
        return $product;
    }

    public function post(Request $request)
    {
        $product = Product::create($request->all());
        return compact('product');
    }

    public function delete(Request $request, Product $product)
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