<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\External\Client;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\User\User;

class Sale extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['payment_type_id', 'card_payment_id', 
        'client_payment', 'total', 'user_id', 
        'client_id', 'branch_id'];


    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function paymentType() {
        return $this->belongsTo(PaymentType::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }
    
}
