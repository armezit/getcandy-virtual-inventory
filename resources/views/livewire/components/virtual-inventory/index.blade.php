<div class="flex-col px-12 space-y-4">

    <div class="grid grid-cols-3 gap-4">
        <x-hub::input.group label="Product" for="productId">
            <div>
                <x-hub::input.select wire:model="productId"
                                     wire:change="onProductChange">
                    <option value readonly>
                        {{ __('adminhub::fieldtypes.dropdown.empty_selection') }}
                    </option>
                    @foreach($this->products as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </x-hub::input.select>
            </div>
        </x-hub::input.group>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <x-hub::input.group label="Product Variant" for="productVariantId">
            <div>
                <x-hub::input.select wire:model="productVariantId"
                                     wire:change="refresh">
                    <option value readonly>
                        {{ __('adminhub::fieldtypes.dropdown.empty_selection') }}
                    </option>
                    @foreach($this->productVariants as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </x-hub::input.select>
            </div>
        </x-hub::input.group>
    </div>

    <div>
        <x-hub::input.group label="Virtual Items" for="data">
            <div class="w-full h-full">
                <livewire:vi.components.handsontable :data="$tableData"
                                                     :config="$tableConfig"></livewire:vi.components.handsontable>
            </div>
        </x-hub::input.group>
    </div>

    <div class="fixed bottom-0 left-0 right-0 z-50 p-6 mr-0 text-right bg-white bg-opacity-75 border-t md:left-64">
        <div class="flex justify-end w-full space-x-6">
            <form action="#" method="POST" wire:submit.prevent="update">
                <x-hub::button type="submit" :disabled="$productId === '' || $productVariantId === ''">{{ __('virtual-inventory::catalogue.virtual-inventory.index.save_btn') }}</x-hub::button>
            </form>
        </div>
    </div>
</div>

