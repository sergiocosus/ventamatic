<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/09/17
 * Time: 10:16 PM
 */

namespace Ventamatic\Core\Product;


use Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\User\User;
use Ventamatic\Exceptions\InventoryException;

class ProductImporterService
{
    private $data;



    public function __construct()
    {
    }


    public function readFromCSV($path)
    {

        echo 'Loading categories' . "\n";
        $categories = Excel::selectSheetsByIndex(0)
            ->load($path)->get()->pluck('categoria')->unique();
        echo 'Saving categories'. "\n";
        $this->createCategories($categories);

        echo 'Loading suppliers'. "\n";
        $suppliers = Excel::selectSheetsByIndex(1)
            ->load($path)->get()->pluck('proveedor')->unique();
        echo 'Saving suppliers'. "\n";
        $this->createSuppliers($suppliers);

        echo 'Loading products'. "\n";
        $products = Excel::selectSheetsByIndex(2)
            ->load($path)->get();

        echo 'Saving products'. "\n";
        $this->createProducts($products);


    }


    /**
     * @param $categories
     */
    public function createCategories($categories)
    {
        foreach ($categories as $categoryName) {
            if (!$categoryName){
                return;
            }
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }

    /**
     * @param $suppliers
     */
    public function createSuppliers($suppliers)
    {
        foreach ($suppliers as $supplierName) {
            if (!$supplierName){
                logger($supplierName);
                return;
            }
            Supplier::firstOrCreate(['name' => $supplierName]);
        }
    }

    /**
     * @param $products
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createProducts($products)
    {
        $branch = Branch::find(1);

        foreach ($products as $productData) {
            echo 'Saving product ' . $productData['nombre']. "\n";
            /** @var Product $product */

            $product = Product::whereDescription($productData['nombre'])->first();

            if (!$product) {
                $product = Product::create([
                        'description' => $productData['nombre'],
                        'unit_id' => 1
                    ]
                );
            }

            $product->global_minimum = $productData['minimo'];
            $product->global_price = $productData['precio'];
            $bar_code = explode("'", $productData['codigo_barras']);

            if (count($bar_code)>1) {
                $bar_code= $bar_code[1];
            } else {
                $bar_code= $bar_code[0];
            }


            if (strtolower($bar_code) == 'null' || $bar_code == 'NA' || '') {
                $product->bar_code = null;
            } else {
                $product->bar_code = $bar_code;
            }

            $product->unit_id = Unit::whereName($productData['unidad'])->first()->id;

            $product->save();

            $categoryNames = explode(',', $productData['categoria']);




            foreach ($categoryNames as $categoryName) {
                $category = Category::firstOrCreate(['name' => trim($categoryName)]);
                if (!$product->categories()->whereId($category->id)->exists()) {
                    $product->categories()->attach($category);
                }
            }


            /*
            if (!$product->supp()->whereId($category->id)->exists()) {
                $product->categories()->attach($category);
            }
            */

            /** @var Inventory $inventory */
            $this->updateInventory($product, $branch, $productData);
        }
    }

    /**
     * @param $product
     * @param $branch
     * @param $productData
     */
    public function updateInventory(Product $product, Branch $branch, $productData)
    {
        $inventory = $product->inventories()->whereBranchId($branch->id)->first();


        $quantity = $productData['cantidad'] - $inventory->quantity;
        $value =  $productData['costo'];
        if ($quantity == 0) {
            return;
        }

        try {
            $this->addInventoryMovement($product, $branch, $quantity, $value);
        } catch (InventoryException $e) {
            $product->unit_id = 2;
            $product->save();
            $product = Product::find($product->id);

            $this->addInventoryMovement($product, $branch, $quantity, $value);
        }
    }

    /**
     * @param $product
     * @param Branch $branch
     * @param $quantity
     */
    public function addInventoryMovement($product, Branch $branch, $quantity, $value)
    {
        $branch->addInventoryMovement(
        // TODO Change this! (This is a system load no a user load)
            User::first(),
            $product,
            [
                'inventory_movement_type_id' => 10,
                'quantity' => $quantity,
                'value' => $value
            ]
        );
    }
}