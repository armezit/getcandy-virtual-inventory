<?php

namespace Armezit\GetCandy\VirtualInventory\Actions;

use Armezit\GetCandy\VirtualInventory\Exceptions\OutOfStockException;
use Armezit\GetCandy\VirtualInventory\Models\VirtualInventoryItem;
use GetCandy\Models\OrderLine;

/**
 * Pull available virtual items for an order line
 */
class PullOrderLineItems
{

    /**
     * Execute the action
     *
     * @param OrderLine $orderLine
     * @return \Illuminate\Support\Collection
     * @throws OutOfStockException
     */
    public function execute(OrderLine $orderLine): \Illuminate\Support\Collection
    {
        $itemIds = VirtualInventoryItem::withoutTrashed()
            ->select('id')
            ->where([
                'purchasable_id' => $orderLine->purchasable_id,
                'purchasable_type' => $orderLine->purchasable_type,
            ])
            ->limit($orderLine->quantity)
            ->get();

        if (count($itemIds) < $orderLine->quantity) {
            throw new OutOfStockException();
        }

        VirtualInventoryItem::withoutTrashed()
            ->whereIn('id', $itemIds)
            ->update([
                'order_line_id' => $orderLine->id,
                'customer_id' => $orderLine->order->customer_id,
                'deleted_at' => now(), // soft delete the items
            ]);

        return $itemIds;
    }

}
