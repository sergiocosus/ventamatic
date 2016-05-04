<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function get()
    {
        return Supplier::all();
    }
    
    public function getSupplier(Supplier $supplier)
    {
        return $supplier;
    }

    public function post(Request $request)
    {
        $supplier = Supplier::create($request->all());
        return compact('supplier');
    }

    public function delete(Request $request, Supplier $supplier)
    {
        if($supplier->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, Supplier $supplier)
    {
        $supplier->fill($request->all());
        $supplier->update();
        return compact('supplier');
    }

}