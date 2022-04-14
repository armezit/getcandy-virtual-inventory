<?php

namespace Armezit\GetCandy\VirtualInventory\Actions;

use Armezit\GetCandy\VirtualInventory\Models\VirtualInventoryItem;

/**
 * Get virtual items which assigned to an order line
 */
class GetOrderLineItems
{

    /**
     * Execute the action
     */
    public function execute(int $orderLineId): \Illuminate\Support\Collection
    {
        return VirtualInventoryItem::withoutTrashed()
            ->where(['order_line_id' => $orderLineId,])
            ->get();
    }

}
