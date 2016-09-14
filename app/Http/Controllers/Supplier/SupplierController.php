<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        $this->can('supplier-get');

        $suppliers = Supplier::get();

        return $this->success(compact('suppliers'));
    }
    
    public function getSupplier(Supplier $supplier)
    {
        $this->can('supplier-get-detail');

        $supplier->load('supplierCategory', 'brands');
        return $this->success(compact('supplier'));
    }

    public function post(Request $request)
    {
        $this->can('supplier-create');

        $supplier = Supplier::create($request->all());
        $supplier->brands()
            ->sync($request->get('brands', []));

        $supplier->load('supplierCategory', 'brands');
        return $this->success(compact('supplier'));
    }

    public function delete(Request $request, Supplier $supplier)
    {
        $this->can('supplier-delete');

        if($supplier->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Supplier $supplier)
    {
        $this->can('supplier-edit');

        $supplier->fill($request->all());
        $supplier->update();
        $supplier->brands()
            ->sync($request->get('brands', []));
        $supplier->load('supplierCategory', 'brands');

        return $this->success(compact('supplier'));
    }

}