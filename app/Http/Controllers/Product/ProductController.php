<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
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
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Product $product)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Product $product)
    {
        /* TODO Fill this method*/
    }

}