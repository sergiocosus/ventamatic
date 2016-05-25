<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function get()
    {
        $suppliers = Supplier::with('supplierCategory')->get();

        return $this->success(compact('suppliers'));
    }
    
    public function getSupplier(Supplier $supplier)
    {
        return $this->success(compact('supplier'));
    }

    public function post(Request $request)
    {
        $supplier = Supplier::create($request->all());
        return $this->success(compact('supplier'));
    }

    public function delete(Request $request, Supplier $supplier)
    {
        if($supplier->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Supplier $supplier)
    {
        $supplier->fill($request->all());
        $supplier->update();
        
        return $this->success(compact('supplier'));
    }

}