@php
    $sizeChart = app('Webkul\SizeChart\Http\Controllers\Admin\SizeChartController');

    $templates = $sizeChart->getAll($product->type);
    
    if($product->parent_id) {
        $sproductId = $product->parent_id;
    } else {
        $sproductId = $product->id;
    }

    $savedTemp = $sizeChart->savedTemp($sproductId);
@endphp
@if ($product->type == 'simple' || $product->type == 'configurable' || $product->type == 'virtual')
<accordian :title="'{{ __('sizechart::app.layouts.sizechart') }}'" :active="false">
    <div slot="body">
        <select-product-sizechart></select-product-sizechart>               
    </div>
</accordian>
@push('scripts')
    @parent

    <script type="text/x-template" id="select-product-sizechart-template">
        <div>
            <div class="control-group" >
            @if($savedTemp)
            <p>Saved Template: <b>{{ $savedTemp->template_name }}</b></p>
            @else
            <p>Saved Template: <b>None</b></p>
            @endif
            </div>
            <div class="control-group" :class="[errors.has('sizechart_id') ? 'has-error' : '']" >
                <label for="sizechart_id" >{{ __('sizechart::app.sizechart.template.select-template') }}</label>
                <select class="control" v-model="selectedSizechart" @change="selectSizechart($event)" id="sizechart_id" name="sizechart_id" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.select-template') }}&quot;">
                    <option value="0" >{{ __('sizechart::app.sizechart.template.select-none') }}</option>                
                    <option v-for="(option, index) in selectOptions" :value="option.id">
                            @{{ option.template_name }}
                    </option>
                </select>
                <span class="control-error" v-if="errors.has('sizechart_id')">@{{ errors.first('sizechart_id') }}</span>
            </div>     
        </div>
    </script>

   

    <script>
        Vue.component('select-product-sizechart', {

            template: '#select-product-sizechart-template',

            data: function() {
                return {
                    savedOption: @json($savedTemp),
                    selectedSizechart: '',
                    selectOptions: @json($templates)
                }
            },

            mounted: function() {

                if (this.savedOption) {
                    this.selectedSizechart = this.savedOption.id     
                } else {
                    this.selectedSizechart = '0'
                }
            },

            methods: {
                selectSizechart: function() {
                    this.$http.get(`{{ route('sizechart.shop.save.template') }}?product-id={{ $sproductId }}&sizechart-id=` + event.target.value)
                        .then(response => {
                            if (response.data.status){
                                // window.flashMessages = [{
                                //     'type': 'alert-success',
                                //     'message': "{{ __('sizechart::app.sizechart.template.save-successfully') }}"
                                // }];

                                // this.$root.addFlashMessages()
                            }else{
                                window.flashMessages = [{
                                    'type': 'alert-error',
                                    'message': "{{ __('sizechart::app.sizechart.template.custom-option-not-available') }}"
                                }];

                                this.$root.addFlashMessages()
                            }
                        })
                        .catch(error => {
                            window.flashMessages = [{
                                'type': 'alert-error',
                                'message': "{{ __('error.something_went_wrong') }}"
                            }];

                            this.$root.addFlashMessages()
                        })
                }
            }
        });
    </script>
@endpush
@endif
