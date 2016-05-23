<?php namespace Ventamatic\Core\Branch;

use DB;
use Exception;
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
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity','price');
    }


    public static function doSale(User $user,
                                  Client $client,
                                  Branch $branch,
                                  PaymentType $paymentType,
                                  Array $products,
                                  $total, $client_payment, $card_payment_id = null)
    {
        $sale = new self();
        $sale->client()->associate($client);
        $sale->user()->associate($user);
        $sale->branch()->associate($branch);
        $sale->paymentType()->associate($paymentType);

        $sale->card_payment_id=$card_payment_id;
        $sale->total=$total;
        $sale->client_payment=$client_payment;

        try {
            DB::beginTransaction();
            
            $branch->reductInventory($products);
            $sale->save();
            $calculatedTotal = $sale->attachProducts($products);

            if($total != $calculatedTotal){
                throw new Exception('El total no concuerda');
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $sale;
       
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
