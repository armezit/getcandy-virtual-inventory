<?php

namespace Armezit\GetCandy\VirtualInventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirtualInventoryItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'customer_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
