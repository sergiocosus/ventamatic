<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function put(Request $request, Branch $branch, Product $product)
    {
        $branch->alterInventory($product,$request->get('quantity'));
        
        return $this->success();
    }

    public function get(Branch $branch, Product $product)
    {
        $inventory = $product->inventories()
            ->whereBranchId($branch->id)
            ->notDeletedProduct()
            ->with('product')
            ->with('product.brand')
            ->with('product.categories')
            ->with('product.unit')
            ->first();
        
        return $this->success(compact('inventory'));
    }

    public function getAll(Branch $branch)
    {
        $inventories = Inventory::whereBranchId($branch->id)
            ->notDeletedProduct()
            ->with('product')
            ->with('product.brand')
            ->with('product.categories')
            ->with('product.unit')
            ->get();

        return $this->success(compact('inventories'));
    }

    public function getSearch(Request $request, Branch $branch)
    {
        $search = $request->get('search');
        $inventories = Inventory::whereBranchId($branch->id)
            ->search($search)
            ->notDeletedProduct()
            ->with('product')
            ->get();

        return $this->success(compact('inventories'));
    }

    public function getBarCode(Request $request, Branch $branch){
        if($bar_code = $request->get('bar_code')) {
            $inventory = Inventory::whereBranchId($branch->id)
                ->notDeletedProduct()
                ->whereProductBarCode($bar_code)
                ->with('product')
                ->first();
            if($inventory){
                return $this->success(compact('inventory'));
            } else {
                return $this->error(400, \Lang::get('products.not_found_bar_code',compact('bar_code')));
            }
        }
    }
}