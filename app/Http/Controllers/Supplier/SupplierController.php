<?php namespace Ventamatic\Http\Controllers\Supplier;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function get(Supplier $supplier= null)
    {
        if($supplier)
        {
            return $supplier;
        }

        return Supplier::all();
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Supplier $supplier)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Supplier $supplier)
    {
        /* TODO Fill this method*/
    }

}