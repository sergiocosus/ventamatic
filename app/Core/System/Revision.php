<?php namespace Ventamatic\Core\System;

use Venturecraft\Revisionable\Revision as RevisionModel;

class Revision extends RevisionModel
{
    protected $casts = [
        'id' => 'integer',
        'revisionable_type' => 'integer',
        'revisionable_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $fillable = ['id', 'revisionable_type', 'revisionable_id', 'user_id', 'key', 'old_value', 'new_value'];



}
