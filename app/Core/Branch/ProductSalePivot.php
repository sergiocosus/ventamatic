<?php namespace Ventamatic\Core\Branch;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 2/08/16
 * Time: 10:58 PM
 */



class ProductSalePivot extends Pivot
{
    protected $casts = [
        'quantity' => 'double',
        'price' => 'double',
    ];
}