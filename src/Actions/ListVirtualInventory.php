<?php

namespace Armezit\GetCandy\VirtualInventory\Actions;

use Armezit\GetCandy\VirtualInventory\Models\VirtualInventoryItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * List available virtual inventory items
 */
class ListVirtualInventory
{

    /**
     * Execute the action
     *
     * @param string|null $purchasableType
     * @param string|null $purchasableId
     * @return Collection
     */
    public function execute(?string $purchasableType = null, ?string $purchasableId = null): Collection
    {
        $query = VirtualInventoryItem::withoutTrashed();

        if ($purchasableType !== null) {
            $query->where('purchasable_type', $purchasableType);
        }

        if ($purchasableId !== null) {
            $query->where('purchasable_id', $purchasableId);
        }

        return $query->get();
    }

}
