<?php namespace Ventamatic;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model {

    protected $fillable = ['id', 'revisionable_type', 'revisionable_id', 'user_id', 'key', 'old_value', 'new_value'];



}
