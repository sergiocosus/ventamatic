<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 3/09/16
 * Time: 11:21 PM
 */

namespace Ventamatic\Core\Product;


class ProductService
{
    public function create(Array $data)
    {
        $product = Product::create($data);
        $product->categories()
            ->sync(array_get($data,'categories',[]));
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