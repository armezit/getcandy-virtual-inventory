<?php

namespace Armezit\GetCandy\VirtualInventory\Http\Livewire\Pages;

use Livewire\Component;
use function view;

class VirtualInventoryIndex extends Component
{
    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('virtual-inventory::livewire.pages.virtual-inventory.index')
            ->layout('virtual-inventory::layouts.app', [
                'title' => 'Virtual Inventory',
            ]);

    }
}
