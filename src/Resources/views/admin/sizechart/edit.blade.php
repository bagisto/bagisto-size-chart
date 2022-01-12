@extends('admin::layouts.content')

@section('page_title')
    {{ __('sizechart::app.sizechart.template.add-temp-title') }}
@stop

@section('css')
    <style>

        .custom_input {
            height:28px;
            text-align: center;
            font-weight: bold;
        }
        .custom_input_t {
            height:28px;
            text-align: center;
        }
        .customOption {
            display: flex;
        }
        .customSpan{
            margin:2px;
        }
        .customOptionDiv {
            padding-top: 20px;
            padding-bottom: 20px;
            overflow: scroll;
        }
    </style>
@stop

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ route('admin.dashboard.index') }}';"></i>

                        {{ __('sizechart::app.sizechart.template.add-temp-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('sizechart::app.sizechart.template.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()
                @if($sizeChart->template_type == 'simple')
                <input type="hidden" name="template_type" value="simple"/>
                @else
                <input type="hidden" name="template_type" value="configurable"/>
                @endif
                
                <input type="hidden" name="template_id" value="{{ $sizeChart->id }}"/>

                {!! view_render_event('bagisto.admin.sizechart.template.create_simple_template.before') !!}

                <accordian :title="'{{ __('sizechart::app.sizechart.template.add-simple-temp') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group" :class="[errors.has('template_name') ? 'has-error' : '']">
                            <label for="template_name" class="required">{{ __('sizechart::app.sizechart.template.template-name') }}</label>
                            <input type="text" v-validate="{ required: true }" class="control" id="template_name" name="template_name" value="{{ $sizeChart->template_name ?: old('template_name') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.template-name') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('template_name')">@{{ errors.first('template_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('template_code') ? 'has-error' : '']">
                            <label for="template_code" class="required">{{ __('sizechart::app.sizechart.template.template-code') }}</label>
                            <input type="text" v-validate="{ required: true }" class="control" id="template_code" name="template_code" value="{{ $sizeChart->template_code ?: old('template_code') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.template-code') }}&quot;" disabled/>
                            <span class="control-error" v-if="errors.has('template_code')">@{{ errors.first('template_code') }}</span>
                        </div>
                        
                        <add-custom-options></add-custom-options>

                        <div class="control-group image" :class="[errors.has('images') ? 'has-error' : '']">
                            <label for="images" >{{ __('sizechart::app.sizechart.template.template-image') }}</label>
                            @if($sizeChart->image_path)
                                <image-wrapper
                                    :multiple="false"
                                    input-name="images"
                                    :images='"{{ url()->to('/') . '/storage/' . $sizeChart->image_path }}"'
                                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
                                </image-wrapper>
                            @else
                                <image-wrapper
                                    :multiple="false"
                                    input-name="images"
                                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
                                </image-wrapper>
                            @endif
                        </div>

                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.sizechart.template.create_simple_template.after') !!}

            </div>

        </form>
    </div>
@stop

@push('scripts')

<script type="text/x-template" id="add-custom-options-template">
    @if ($sizeChart->template_type == 'simple')    
    <div class="control-group" :class="[errors.has('config_option') ? 'has-error' : '']" v-if="! showCustomOptions">
        <label for="config_option" class="required">{{ __('sizechart::app.sizechart.template.config-option') }}</label>
        <input type="text" v-model="customOptionValues" v-validate="{ required: true }" class="control" id="config_option" name="config_option" value="{{ request()->input('config_option') ?: old('config_option') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.config-option') }}&quot;"/>
        <span class="control-error" v-if="errors.has('config_option')">@{{ errors.first('config_option') }}</span>
        <span class="control-info mt-10">{{ __('sizechart::app.sizechart.template.config-option-info') }}</span>
        <br>
        <button type="button" class="btn btn-lg btn-primary" @click="addCustomOption()">
            {{ __('sizechart::app.sizechart.template.continue') }}
        </button>
    </div>
    @else
    <div class="control-group" :class="[errors.has('config_option') ? 'has-error' : '']" v-if="! showCustomOptions">
        <label for="config_option" class="required">{{ __('sizechart::app.sizechart.template.config-option') }}</label>
        <select v-validate="'required'" v-model="attribute" class="control"  id="select_attribute" @change="selectAttribute($event)" name="select_attribute" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.select-attribute') }}&quot;">
            <option value="">{{ __('sizechart::app.sizechart.template.select-attribute') }}</option>
            
            @foreach ($attributes as $attribute)
                @if ($attribute->name != 'Price')
                    <option value="{{ $attribute->id }}">
                        {{ $attribute->name ? $attribute->name : $attribute->admin_name }}
                    </option>
                @endif
            @endforeach
            
        </select>
        <span class="control-error" v-if="errors.has('config_option')">@{{ errors.first('config_option') }}</span>
    </div>
    <input type="hidden" v-model="configAttribute" name="config_attribute" value="0"/>
    @endif

    <div class="control-group" v-else>
        <div>
            <button type="button" class="btn btn-lg btn-primary" @click="backtoinput()">
                {{ __('sizechart::app.sizechart.template.back') }}
            </button>
            <button type="button" class="btn btn-lg btn-primary" @click="addCustomRow()">
                {{ __('sizechart::app.sizechart.template.add-row') }}
            </button>
        </div>
        <div class="customOptionDiv">
            <div class="customOption">
                <span class="customSpan">
                    <input type="text" class="custom_input" v-model="label"  v-validate="{ required: true }"  name="formname[0][label]" placeholder="Enter Option Name"/>
                </span>
                <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                    <input type="text" class="custom_input"  :value="inputOption" :name="'formname[0]['+ inputOption +']'" readonly/>
                </span>
            </div>
        
            <div class="customOption" v-for='(addRow, key) in addRows'>
                <div v-if="addRow.name" style="display:flex;">
                    <span class="customSpan">
                        <input type="text" class="custom_input_t" v-validate="{ required: true }" :value="addRow.name"  :name="'formname['+ addRow.row +'][name]'"/>
                    </span>
                    <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                        <input type="text" class="custom_input_t" v-validate="{ required: true }"  :value="addRow[``+ inputOption +``]" :name="'formname['+ addRow.row +']['+ inputOption +']'"/>
                    </span>
                    <span>
                        <i class="icon remove-icon" @click="removeCustomRow(key)"></i>
                    </span>
                </div>

                <div v-else style="display:flex;">
                    <span class="customSpan">
                        <input type="text" class="custom_input_t" v-validate="{ required: true }"  :name="'formname['+ addRow.row +'][name]'"/>
                    </span>
                    <span v-for='(inputOption, index) in inputOptions' class="customSpan">
                        <input type="text" class="custom_input_t" v-validate="{ required: true }"   :name="'formname['+ addRow.row +']['+ inputOption +']'"/>
                    </span>
                    <span>
                        <i class="icon remove-icon" @click="removeCustomRow(key)"></i>
                    </span>
                </div>
                
            </div>

        </div>
    </div>
</script>

<script>
        Vue.component('add-custom-options', {

            template: '#add-custom-options-template',

            data: function() {
                return {
                    label: @json($label),
                    customOptionValues: @json($customOptions),
                    showCustomOptions: false,
                    inputOptions: '',
                    counter: @json($addRows).length,
                    configAttribute: '',
                    attribute: '',
                    addRows: @json($addRows),
                }
            },

            mounted: function() {
                if ( @json($addRows).length) {
                    showCustomOptions = true;
                    this.addCustomOption();
                }
            },

            methods: {
                selectAttribute: function(event) {
                    this.configAttribute = event.target.value;

                    this.$http.get(`{{url('/')}}/admin/sizechart/attribute?attribute-id=` + event.target.value)
                        .then(response => {
                            if (response.data.status){
                                this.customOptionValues = response.data.customOptionValues;
                                this.addCustomOption();
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
                },
                
                addCustomOption: function() {
                    if (this.customOptionValues != '' || this.customOptionValue != null) {
                        this.showCustomOptions = true;
                        this.inputOptions = this.customOptionValues.split(",");    
                    } else {
                        window.flashMessages = [{
                            'type': 'alert-error',
                            'message': "{{ __('sizechart::app.sizechart.template.empty-custom-option') }}"
                        }];

                        this.$root.addFlashMessages()
                    }
                    
                },

                backtoinput: function() {
                    this.showCustomOptions = false;
                    this.counter = 0;
                    this.addRows = [];
                },

                addCustomRow: function() {
                    this.counter += 1;
                    this.addRows.push({
                        row :this.counter
                    });
                },

                removeCustomRow: function(key) {
                    this.addRows.splice(key, 1)
                }
            }
        });
</script>
    
@endpush