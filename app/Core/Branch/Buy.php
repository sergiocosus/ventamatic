<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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

    public static function doBuy(User $user,
                                  Branch $branch,
                                  PaymentType $paymentType,
                                  Array $products,
                                  $total, $client_payment, $card_payment_id = null)
    {
        $buy = new self();
        $buy->client()->associate($client);
        $buy->user()->associate($user);
        $buy->branch()->associate($branch);
        $buy->paymentType()->associate($paymentType);

        $buy->card_payment_id=$card_payment_id;
        $buy->total=$total;
        $buy->client_payment=$client_payment;

        try {
            DB::beginTransaction();

            $branch->reductInventory($products);
            $buy->save();
            $calculatedTotal = $buy->attachProducts($products);

            if($total != $calculatedTotal){
                throw new Exception('El total no concuerda');
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $buy;

    }

    private function attachProducts(Array $products){
        $productsToAttach = [];
        $total = 0;
        foreach ($products as $product_data)
        {
            /** @var Product $product */
            $product = Product::findOrFail($product_data['product_id']);

            $price = $product->getCorrectPrice();
            $quantity = $product_data['quantity'];

            $total += $price * $quantity;

            $productsToAttach[$product->id] = compact('price', 'quantity');
        }
        $this->products()->attach($productsToAttach);
        return $total;
    }
    

}
