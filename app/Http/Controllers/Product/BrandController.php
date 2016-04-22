<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function get()
    {
        $brand=Brand::all();

        return compact('brand');
    }

    public function getBrand(Brand $brand)
    {
        return compact('brand');
    }

    public function post(Request $request)
    {
        $brand = Brand::create($request->all());
        return compact('brand');
    }

    public function delete(Request $request, Brand $brand)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Brand $brand)
    {
        $brand->fill($request->all());
        $brand->update();
        return compact('brand');
    }

}