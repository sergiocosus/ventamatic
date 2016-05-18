<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function put(Request $request, Branch $branch, Product $product)
    {
        $branch->alterInventory($product,$request);
        return ['success' => true];
    }

    public function get(Product $product, Branch $branch)
    {
        return $product->inventories()->whereBranchId($branch->id)->first();
    }

    public function getAll(Branch $branch)
    {

        $inventories=$branch->inventories;
        return compact('inventories');
    }
}