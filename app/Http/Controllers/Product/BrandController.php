<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        $brands = Brand::all();

        return $this->success(compact('brands'));
    }

    public function getBrand(Brand $brand)
    {
        return $this->success(compact('brand'));
    }

    public function post(Request $request)
    {
        $brand = Brand::create($request->all());
        return $this->success(compact('brand'));
    }

    public function delete(Request $request, Brand $brand)
    {
        if($brand->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Brand $brand)
    {
        $brand->fill($request->all());
        $brand->update();
        return $this->success(compact('brand'));
    }

}