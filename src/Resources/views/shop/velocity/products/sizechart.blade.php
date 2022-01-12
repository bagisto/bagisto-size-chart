@php
    $sizeChart = app('Webkul\SizeChart\Http\Controllers\Shop\SizeChartController');
    
    if($product->parent_id) {
        $sproductId = $product->parent_id;
    } else {
        $sproductId = $product->id;
    }
    $template = $sizeChart->getSizeChart($sproductId);
@endphp

@if (core()->getConfigData('catalog.products.size-chart.enable-sizechart'))
    @if($template)

        <view-size-chart></view-size-chart>

        @push('scripts')

        <script type="text/x-template" id="view-size-chart-template" >
        <a href="#" @click="showSizeChart" style="float:right; font-size:18px;">{{ __('sizechart::app.sizechart.template.view-size-chart') }}</a>
        </script>

        <script>
                Vue.component('view-size-chart', {

                    template: '#view-size-chart-template',

                    data: function() {
                        return {
                        }
                    },

                    methods: {
                        showSizeChart: function () {
                            this.$root.showModal('downloadDataGrid');
                        },
                    }
                });
        </script>
            
        @endpush

    @endif
@endif