<?php

namespace Armezit\GetCandy\VirtualInventory\Seeders;

use GetCandy\Models\Attribute;
use GetCandy\Models\AttributeGroup;
use GetCandy\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Install extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {

            $attributeGroup = AttributeGroup::updateOrCreate([
                'attributable_type' => Product::class,
                'name' => ['en' => 'Virtual Inventory'],
                'handle' => 'product_virtual_inventory',
                'position' => 2,
            ]);

            $attributeGroup->attributes()->saveMany([
                new Attribute([
                    'attribute_type' => Product::class,
                    'position' => 1,
                    'name' => ['en' => 'Has Virtual Inventory'],
                    'handle' => 'has_virtual_inventory',
                    'section' => 'main',
                    'type' => \GetCandy\FieldTypes\Toggle::class,
                    'required' => false,
                    'configuration' => ['on_value' => null, 'off_value' => null],
                    'system' => true,
                    'filterable' => false,
                    'searchable' => false,
                ]),
                new Attribute([
                    'attribute_type' => Product::class,
                    'position' => 2,
                    'name' => ['en' => 'Virtual Inventory Attributes'],
                    'handle' => 'virtual_inventory_attributes',
                    'section' => 'main',
                    'type' => \GetCandy\FieldTypes\ListField::class,
                    'required' => false,
                    'configuration' => ['max_items' => null],
                    'system' => true,
                    'filterable' => false,
                    'searchable' => false,
                ]),
            ]);

        });
    }
}
