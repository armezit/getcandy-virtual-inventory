<?php

namespace Armezit\GetCandy\VirtualInventory\Http\Livewire\Components;

use Armezit\GetCandy\VirtualInventory\Actions\UpdateVirtualInventory;
use Armezit\GetCandy\VirtualInventory\Models\VirtualInventoryItem;
use GetCandy\FieldTypes\Toggle;
use GetCandy\Hub\Http\Livewire\Traits\Notifies;
use GetCandy\Models\Product;
use GetCandy\Models\ProductVariant;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use function view;

class VirtualInventoryIndex extends Component
{

    use Notifies;

    protected $queryString = [
        'productId' => ['except' => '', 'as' => 'pid'],
        'productVariantId' => ['except' => '', 'as' => 'pvid'],
    ];

    /**
     * @var string
     */
    public string $productId = '';

    /**
     * @var string
     */
    public string $productVariantId = '';

    /**
     * @var string
     */
    protected string $purchasableType = ProductVariant::class;

    /**
     * @var array
     */
    public array $tableData;

    /**
     * @var array
     */
    public array $tableConfig = [
        'startRows' => 1,
        'startCols' => 1,
        'minSpareRows' => 1,
        'contextMenu' => true,
        'height' => 'auto',
        'width' => '100%',
        'manualColumnResize' => true,
        'manualRowResize' => true,
        'stretchH' => 'last',
        'className' => 'htCenter',
        'rowHeaders' => false,
        'colHeaders' => ['ID'],
        'dataSchema' => ['id' => null],
        'columns' => [
            ['data' => 'id', 'readOnly' => true]
        ],
        'colWidths' => [50],
        'licenseKey' => 'non-commercial-and-evaluation',
    ];

    protected $listeners = [
        'handsontable:change' => 'onDataChange',
        'handsontable:removeRow' => 'onDataRowRemove'
    ];

    /**
     * @return void
     */
    public function mount()
    {
        $this->productId = request()->query->has('pid') ? request()->query->get('pid') : '';
        $this->productVariantId = request()->query->has('pvid') ? request()->query->get('pvid') : '';

        $this->reloadData();
    }

    /**
     * @return Product|null
     */
    public function getProductProperty(): ?Product
    {
        return Product::find($this->productId);
    }

    /**
     * @return array<string, string>
     */
    public function getProductsProperty(): array
    {
        // get all products which their variants has at least one option-value
        return Product::withoutTrashed()
            ->has('variants.values')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->translateAttribute('name'),
                ];
            })
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return ProductVariant|null
     */
    public function getProductVariantProperty(): ?ProductVariant
    {
        return ProductVariant::find($this->productVariantId);
    }

    /**
     * @return array<string, string>
     */
    public function getProductVariantsProperty(): array
    {
        // get product variants which have at least one option-value
        return ProductVariant::where(['product_id' => $this->productId])
            ->has('values')
            ->get()
            ->map(function ($productVariant) {
                return [
                    'id' => $productVariant->id,
                    'name' => $productVariant->getOption() ?: 'Default',
                ];
            })
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Get selected table data
     * @return void
     */
    public function reloadData()
    {
        if (!$this->productId) {
            $this->tableData = [];
            $this->tableConfig['colHeaders'] = ['No any attributes has been defined.'];
            return;
        }

        $this->tableData = VirtualInventoryItem::query()
            ->where([
                'purchasable_type' => $this->purchasableType,
                'purchasable_id' => $this->productVariantId,
            ])
            ->get()
            ->map(function ($item) {
                $data = json_decode($item->attributes);
                $data->id = $item->id;
                return $data;
            })
            ->all();

        /*
         * Build table schema for handsontable
         *
         * example:
         *      colHeaders: ['ID', 'Name'],
         *      dataSchema: { id: null, name: null },
         *      columns: [
         *          { data: 'id' },
         *          { data: 'name' },
         *      ],
         *      data: [
         *          {"id":"1","name":"2"}
         *      ]
         */

        // get product-type attributes
        $attributes = $this->product->productType
            ->productAttributes
            ->sortBy('position')
            ->values()
            ->filter(function ($attribute) {
                return
                    $attribute->attribute_type === Product::class &&
                    $attribute->type === Toggle::class;
            });

        // reset data-specific tableConfig properties
        $this->tableConfig['colHeaders'] = ['ID'];
        $this->tableConfig['dataSchema'] = ['id' => null];
        $this->tableConfig['columns'] = [['data' => 'id', 'readOnly' => true]];

        foreach ($attributes as $attribute) {
            $this->tableConfig['colHeaders'][] = $attribute->translate('name');
            $this->tableConfig['dataSchema'][(string)$attribute->id] = null;
            $this->tableConfig['columns'][] = ['data' => (string)$attribute->id, 'type' => 'text',];
        }

    }

    public function refresh()
    {
        $this->reloadData();
        $this->dispatchBrowserEvent('handsontable:refresh', [
            'config' => $this->tableConfig,
            'data' => $this->tableData,
        ]);
    }

    public function onProductChange()
    {
        $this->productVariantId = '';
        $this->refresh();
    }

    public function onDataChange($changes, $data)
    {
        $this->tableData = $data;
    }

    /**
     * @param int $index Visual index of starter row
     * @param int $amount An amount of removed rows
     * @param $data
     * @return void
     */
    public function onDataRowRemove(int $index, int $amount, $data)
    {
        $this->tableData = $data;
    }

    /**
     * Register the validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'tableConfig' => 'array',
            'tableData' => 'array',
        ];
    }

    /**
     * Cleanup data to be saved
     * @return array
     */
    private function sanitizeData(): array
    {
        $data = [];

        // remove empty rows
        foreach ($this->tableData as $i => $row) {
            if (!empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * Update virtual inventory items.
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        $data = $this->sanitizeData();

        $columnMapping = [];
        foreach ($this->tableConfig['columns'] as $index => $col) {
            $columnMapping[$index] = $col['data'];
        }

        App::make(UpdateVirtualInventory::class)->execute(
            $this->purchasableType,
            $this->productVariantId,
            $columnMapping,
            $data,
        );

        $this->notify('Virtual inventory updated', 'hub.virtual-inventory.index', [
            'pid' => $this->productId,
            'pvid' => $this->productVariantId,
        ]);
    }

    public function render()
    {
        return view('virtual-inventory::livewire.components.virtual-inventory.index', ['data' => $this->tableData])
            ->layout('adminhub::layouts.base');
    }

}
