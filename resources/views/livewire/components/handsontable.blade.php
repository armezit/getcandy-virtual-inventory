<div id="hot"
     style="direction: ltr !important;"
     x-data="{
        hot: null,
        data: null,
        init() {
            const container = document.getElementById('hot');
            this.hot = new Handsontable(container, {
                'licenseKey': 'non-commercial-and-evaluation'
            });
            this.defineEvents([]);
            this.refresh(@js($config), @js($data));
        },
        refresh(config, data) {
            this.hot.updateSettings(config);
            this.data = data;
            this.hot.loadData(JSON.parse(JSON.stringify(data)));
        },
        defineEvents() {
            this.hot.addHook('afterChange', (change, source) => {
                if (source === 'loadData') {
                  return; //don't save this change
                }
                this.data = this.hot.getData();
                $wire.emit('handsontable:change', change, this.data);
            })

            this.hot.addHook('afterRemoveRow', (index, amount, physicalRows, source) => {
                if (source === 'loadData') {
                  return; //don't save this change
                }
                this.data = this.hot.getData();
                $wire.emit('handsontable:removeRow', index, amount, this.data);
            })
        },
        onChange() {

        }
     }"
     @handsontable:refresh.window="refresh($event.detail.config, $event.detail.data)"
>
</div>

@push('scripts')
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"/>
@endpush
