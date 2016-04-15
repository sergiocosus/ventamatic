<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\User\User;

class Buy extends RevisionableBaseModel {
    
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;
    
    protected $fillable = ['id', 'payment_type_id', 'card_payment_id', 'iva',
        'ieps', 'total', 'user_id', 'supplier_id', 'branch_id',
        'supplier_bill_id'];


    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function paymentType() {
        return $this->belongsTo(PaymentType::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }
    

}
