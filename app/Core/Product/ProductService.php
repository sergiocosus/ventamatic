<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 3/09/16
 * Time: 11:21 PM
 */

namespace Ventamatic\Core\Product;


use Ventamatic\Core\Branch\Branch;

class ProductService
{
    public function create(Array $data)
    {
        /** @var Product $product */
        try {
            \DB::beginTransaction();
            $product = Product::create($data);
            $product->categories()
                ->sync(array_get($data,'categories',[]));
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

        $this->lazyLoad($product);

        return $product;
    }

    public function update($product, Array $data)
    {
        $product->fill($data);
        $product->update();
        $product->categories()
            ->sync(array_get($data,'categories',[]));
        $this->lazyLoad($product);

        return $product;
    }

    private function lazyLoad($product) {
        $product->load('categories', 'unit', 'brand');
    }


}