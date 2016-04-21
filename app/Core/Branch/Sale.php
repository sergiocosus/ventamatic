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
        return $this->belongsToMany(Product::class);
    }


    public static function doSale(User $user, Client $client, 
                                  Branch $branch, Array $products)
    {
        $sale = new self();
        $sale->client_id = $client->id;
        $sale->client()->associate($client);
        $sale->user()->associate($user);
        $sale->branch()->associate($branch);

        try {
            DB::beginTransaction();
            
            $branch->reductInventory($products);
            $sale->save();
            $sale->attachProducts($products);
            
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
       
    }
    
    private function attachProducts(Array $products){
        $productsToAttach = [];
        foreach ($products as $product)
        {
            $productsToAttach[$product->id] = [
                'price' => $product['product']->getCorrectPrice(),
                'quantity' => $product['quantity'],
            ];
        }
        $this->products()->attach($productsToAttach);
    }
}
