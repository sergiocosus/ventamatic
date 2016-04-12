<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10/04/16
 * Time: 08:23 PM
 */

namespace Ventamatic\Core\System;


class RevisionableBaseModel extends BaseModel
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    protected $revisionEnabled = true;
    protected $revisionCreationsEnabled = true;

}