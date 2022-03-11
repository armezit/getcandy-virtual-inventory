<?php

namespace Armezit\GetCandy\VirtualInventory\Http\Livewire\Components;

use Livewire\Component;
use function view;

class Handsontable extends Component
{

    /**
     * @var array
     */
    public array $data;

    public array $config;

    public function render()
    {
        return view('virtual-inventory::livewire.components.handsontable');
    }
}
