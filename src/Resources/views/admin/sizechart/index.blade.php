@extends('admin::layouts.content')

@section('page_title')
    {{ __('sizechart::app.sizechart.template.title') }}
@stop

@section('content')
    <div class="content" style="height: 100%;">
        <?php $locale = request()->get('locale') ?: null; ?>
        <?php $channel = request()->get('channel') ?: null; ?>
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('sizechart::app.sizechart.template.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('sizechart.admin.index.create', ['type' => '0'])  }}" class="btn btn-lg btn-primary">
                    {{ __('sizechart::app.sizechart.template.add-configurable') }}
                </a>
            </div>

            <div class="page-action">
                <a href="{{ route('sizechart.admin.index.create', ['type' => '1']) }}" class="btn btn-lg btn-primary">
                    {{ __('sizechart::app.sizechart.template.add-simple') }}
                </a>
            </div>

        </div>

        {!! view_render_event('bagisto.admin.sizechart.template.list.before') !!}

        <div class="page-content">
        {!! app('Webkul\SizeChart\Datagrids\TemplateDataGrid')->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.sizechart.template.list.after') !!}

    </div>
@stop

@push('scripts')
    <script>

        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

    </script>
@endpush