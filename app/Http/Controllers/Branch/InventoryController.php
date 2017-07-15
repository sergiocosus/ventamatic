<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function post(Request $request, Branch $branch, Product $product)
    {
        $this->canOnBranch('inventory-edit', $branch);

        $movements = $branch->addInventoryMovement(
            \Auth::user(),
            $product,
            $request->all());

        $inventories = [];
        /** @var InventoryMovement $movement */
        foreach ($movements as $movement) {
            $inventories[] = $this->loadProduct($movement->branch, $movement->product);
        }

        return $this->success(compact('inventories'));
    }

    public function put(Request $request, Branch $branch, Product $product)
    {
        $this->canOnBranch('inventory-edit', $branch);

        $inventory = Inventory::whereBranchId($branch->id)
            ->whereProductId($product->id)->first();
        if (!$inventory) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }

        $inventory->fill($request->only(['price', 'minimum']));
        $inventory->update();

        return $this->get($branch, $product);
    }

    public function get(Branch $branch, Product $product)
    {
        $this->canOnBranch('inventory-get-detail', $branch);

        $inventory = $this->loadProduct($branch, $product);

        return $this->success(compact('inventory'));
    }

    private function loadProduct(Branch $branch, Product $product)
    {
        return $product->inventories()
            ->whereBranchId($branch->id)
            ->notDeletedProduct()
            ->with('product', 'product.brand', 'product.categories','product.unit')
            ->first();
    }

    public function getAll(Branch $branch)
    {
        $this->canOnBranch('inventory-get', $branch);

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
        $this->canOnBranch('inventory-get', $branch);

        $search = $request->get('search');
        $inventories = Inventory::whereBranchId($branch->id)
            ->search($search)
            ->groupBy('inventories.id')
            ->notDeletedProduct()
            ->orderBy('quantity', 'desc')
            ->with('product')
            ->get();

        return $this->success(compact('inventories'));
    }

    public function getBarCode(Request $request, Branch $branch){
        $this->canOnBranch('inventory-get', $branch);

        if($bar_code = $request->get('bar_code')) {
            $inventory = Inventory::whereBranchId($branch->id)
                ->notDeletedProduct()
                ->whereProductBarCode($bar_code)
                ->with('product')
                ->first();
            if($inventory){
                return $this->succesPassport::routes()s(compact('inventory'));
            } else {
                return $this->error(400, \Lang::get('products.not_found_bar_code',compact('bar_code')));
            }
        }
    }
}