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
        $this->can('brand-get');

        $brands = Brand::all();

        return $this->success(compact('brands'));
    }

    public function getBrand(Brand $brand)
    {
        $this->can('brand-get-detail');

        return $this->success(compact('brand'));
    }

    public function post(Request $request)
    {
        $this->can('brand-create');

        $brand = Brand::create($request->all());
        return $this->success(compact('brand'));
    }

    public function delete(Request $request, Brand $brand)
    {
        $this->can('brand-delete');

        if($brand->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Brand $brand)
    {
        $this->can('brand-edit');

        $brand->fill($request->all());
        $brand->update();
        return $this->success(compact('brand'));
    }

}