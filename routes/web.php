<?php

use Armezit\GetCandy\VirtualInventory\Http\Livewire\Pages\VirtualInventoryIndex;
use GetCandy\Hub\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
 * Admin Hub Routes
 */
Route::group([
    'prefix' => config('getcandy-hub.system.path', 'hub'),
    'middleware' => ['web'],
], function () {
    Route::group([
        'prefix' => 'virtual-inventory',
        'middleware' => [
            Authenticate::class,
            //'can:catalogue:manage-virtual-inventory',
        ],
    ], function ($router) {

        Route::get('/', VirtualInventoryIndex::class)->name('hub.virtual-inventory.index');

    });
});
