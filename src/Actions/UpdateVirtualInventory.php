<?php

namespace Armezit\GetCandy\VirtualInventory\Actions;

use Armezit\GetCandy\VirtualInventory\Models\VirtualInventoryItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Update available inventory items
 */
class UpdateVirtualInventory
{

    /**
     * Execute the action
     *
     * @param string $purchasableType
     * @param string $purchasableId
     * @param array $columnMapping
     * @param array $data
     * @return void
     * @throws \Throwable
     */
    public function execute(string $purchasableType, string $purchasableId, array $columnMapping, array $data)
    {
        // find index of id column
        $idIndex = array_search('id', $columnMapping);
        unset($columnMapping[$idIndex]);

        if (!array_key_exists($idIndex, $data[0])) {
            $idIndex = 'id';
        }

        // prepare upsert data
        $upsert = [];
        foreach ($data as $row) {

            $id = $row[$idIndex];
            unset($row[$idIndex]);
            $attributes = array_combine(array_values($columnMapping), $row);

            $upsert[] = [
                'id' => $id,
                'purchasable_type' => $purchasableType,
                'purchasable_id' => $purchasableId,
                'attributes' => json_encode($attributes),
            ];
        }

        // find deleted ids
        $allIds = array_filter(array_unique(array_column($upsert, 'id')), 'strlen');
        $deletedIds = VirtualInventoryItem::withoutTrashed()
            ->whereNotIn('id', $allIds)
            ->select('id')
            ->get();

        DB::transaction(function () use ($purchasableId, $purchasableType, $upsert, $deletedIds) {
            VirtualInventoryItem::whereIn('id', $deletedIds)->forceDelete();
            VirtualInventoryItem::upsert($upsert, ['id']);

            if (config('virtual-inventory.catalogue.update_purchasable_stock', false)) {
                $stock = VirtualInventoryItem::withoutTrashed()
                    ->where([
                        'purchasable_type' => $purchasableType,
                        'purchasable_id' => $purchasableId,
                    ])
                    ->count();

                $purchasable = App::make($purchasableType)->find($purchasableId);
                $purchasable->stock = $stock;
                $purchasable->save();
            }
        });
    }

}
