<?php namespace Ventamatic\Core\ThirdParty;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\System\RevisionableBaseModel;

class Client extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'last_name', 'last_name_2', 'email', 
        'phone', 'cellphone', 'address', 'rfc'];


    public function sales() {
        return $this->hasMany(Sale::class);
    }


}
