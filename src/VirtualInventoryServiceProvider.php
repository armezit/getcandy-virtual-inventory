<?php

namespace Armezit\GetCandy\VirtualInventory;

use Armezit\GetCandy\VirtualInventory\Http\Livewire\Components\Handsontable;
use Armezit\GetCandy\VirtualInventory\Http\Livewire\Components\VirtualInventoryIndex;
use GetCandy\Hub\Facades\Menu;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class VirtualInventoryServiceProvider extends ServiceProvider
{

    protected string $root = __DIR__ . '/..';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom("{$this->root}/config/virtual-inventory.php", "virtual-inventory");
    }

    /**
     * Boot up the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom("{$this->root}/routes/web.php");
        $this->loadMigrationsFrom("{$this->root}/database/migrations");
        $this->loadViewsFrom("{$this->root}/resources/views", 'virtual-inventory');
        $this->loadTranslationsFrom("{$this->root}/resources/lang", 'virtual-inventory');

        $this->registerPublishables();
        $this->registerLivewireComponents();

        $this->registerMenuBuilder();
    }

    /**
     * Register our publishables.
     *
     * @return void
     */
    private function registerPublishables()
    {
        $this->publishes([
            "{$this->root}/config/virtual-inventory.php" => config_path("virtual-inventory.php"),
        ], ['getcandy:virtual-inventory:config']);

        $this->publishes([
            "{$this->root}/resources/lang" => $this->app->langPath('vendor/getcandy-virtual-inventory'),
        ], ['getcandy:virtual-inventory:lang']);
    }

    /**
     * Register the hub's Livewire components.
     *
     * @return void
     */
    protected function registerLivewireComponents()
    {
        Livewire::component('vi.components.handsontable', Handsontable::class);
        Livewire::component('vi.components.virtual-inventory.index', VirtualInventoryIndex::class);
    }

    protected function registerMenuBuilder()
    {
        $slot = Menu::slot('sidebar');

        $slot->addItem(function ($item) {
            $item->name(
                __('virtual-inventory::menu.sidebar.virtual-inventory')
            )->handle('hub.virtual-inventory')
                ->route('hub.virtual-inventory.index')
                ->icon('view-boards');
        });
    }

}
