
@php
    $sizeChart = app('Webkul\SizeChart\Http\Controllers\Shop\SizeChartController');

    if($product->parent_id) {
        $sproductId = $product->parent_id;
    } else {
        $sproductId = $product->id;
    }
    $template = $sizeChart->getSizeChart($sproductId);
    
    if($template) {
        $options = $sizeChart->getOptions($template->id);
        
    }
@endphp

@if($template)

@section('css')
    <style>
        .sizechart_th {
            padding: 20px;
            background-color: #4d7ea8;
            color: white;
        }
    </style>
@stop

<modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">Size Chart</h3>
        <div slot="body">
            <div>
                <div style="float: left; padding-bottom:20px;">
                    <img style="width:200px" src="{{ bagisto_asset('storage/' . $template->image_path) }}" onerror="this.src='{{ asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png') }}'">
                </div>
                <div style="display: inline-block;">
                    <table style="min-width:350px">
                        <thead>
                            <tr>
                                <th style="padding: 10px; background-color: #4d7ea8; color: white;">{{json_decode($template->size_chart)[0]->label}}</th>
                            @foreach(json_decode($template->size_chart) as $key => $th)
                                @if($key)
                                <th style="padding: 10px; background-color: #4d7ea8; color: white;">{{$th->name}}</th>
                                @endif
                            @endforeach
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($options as $option)
                                <tr >
                                    <td style="padding: 10px;">{{$option}}</td>
                                    @foreach(json_decode($template->size_chart) as $key => $th)
                                    @if($key)
                                        <td style="padding: 10px;">{{json_decode($template->size_chart)[$key]->$option}}</td>  
                                    @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
</modal>
@endif

