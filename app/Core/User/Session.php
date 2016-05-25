<?php namespace Ventamatic\Core\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class Session extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'user_id'];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }


}
