<?php

namespace Armezit\GetCandy\VirtualInventory\Models;

use GetCandy\Models\Customer;
use GetCandy\Models\OrderLine;
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
        'purchasable_type',
        'purchasable_id',
        'order_line_id',
        'customer_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the order line that owns the item.
     */
    public function orderLine()
    {
        return $this->belongsTo(OrderLine::class, 'order_line_id');
    }

    /**
     * Get the customer that owns the item.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Return the polymorphic relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function purchasable()
    {
        return $this->morphTo();
    }

}
