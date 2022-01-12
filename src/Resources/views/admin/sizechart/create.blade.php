@extends('admin::layouts.content')

@section('page_title')
    {{ __('sizechart::app.sizechart.template.add-temp-title') }}
@stop

@section('css')
    <style>
        .table td .label {
            margin-right: 10px;
        }
        .table td .label:last-child {
            margin-right: 0;
        }
        .table td .label .icon {
            vertical-align: middle;
            cursor: pointer;
        }
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

                {!! view_render_event('bagisto.admin.sizechart.template.create_simple_template.before') !!}

                <accordian :title="'{{ __('sizechart::app.sizechart.template.add-simple-temp') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group" :class="[errors.has('template_name') ? 'has-error' : '']">
                            <label for="template_name" class="required">{{ __('sizechart::app.sizechart.template.template-name') }}</label>
                            <input type="text" v-validate="{ required: true }" class="control" id="template_name" name="template_name" value="{{ request()->input('template_name') ?: old('template_name') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.template-name') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('template_name')">@{{ errors.first('template_name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('template_code') ? 'has-error' : '']">
                            <label for="template_code" class="required">{{ __('sizechart::app.sizechart.template.template-code') }}</label>
                            <input type="text" v-validate="{ required: true }" class="control" id="template_code" name="template_code" value="{{ request()->input('template_code') ?: old('template_code') }}" data-vv-as="&quot;{{ __('sizechart::app.sizechart.template.template-code') }}&quot;"/>
                            <span class="control-error" v-if="errors.has('template_code')">@{{ errors.first('template_code') }}</span>
                        </div>

                        @if($type)
                            @include ('sizechart::admin.sizechart.type.simple')
                        @else
                            @include ('sizechart::admin.sizechart.type.configurable')
                        @endif
                    
                        <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                            <label class="required">{{ __('sizechart::app.sizechart.template.template-image') }}</label>

                            <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="image" :multiple="false"></image-wrapper>

                            <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                @foreach ($errors->get('image.*') as $key => $message)
                                    @php echo str_replace($key, 'Image', $message[0]); @endphp
                                @endforeach
                            </span>
                        </div>
                        
                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.sizechart.template.create_simple_template.after') !!}

            </div>

        </form>
    </div>
@stop

