<?php namespace Ventamatic\Core\External;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\System\RevisionableBaseModel;

class Client extends RevisionableBaseModel {

    use SoftDeletes;
    use SearchableTrait;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'last_name', 'last_name_2', 'email', 
        'phone', 'cellphone', 'address', 'rfc'];

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'last_name' => 10,
            'last_name_2' => 10,
        ]
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function sales() {
        return $this->hasMany(Sale::class);
    }


}
