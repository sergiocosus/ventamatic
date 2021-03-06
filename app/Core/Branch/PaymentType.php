<?php namespace Ventamatic\Core\Branch;

use Ventamatic\Core\System\RevisionableBaseModel;

class PaymentType extends RevisionableBaseModel {

    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function buys() {
        return $this->hasMany(Buy::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }


}
