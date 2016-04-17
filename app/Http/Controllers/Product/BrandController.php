<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function get()
    {
        return Brand::all();
    }

    public function getBrand(Brand $brand)
    {
        return $brand;
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Brand $brand)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Brand $brand)
    {
        /* TODO Fill this method*/
    }

}