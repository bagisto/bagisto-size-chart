<?php

namespace Webkul\SizeChart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\SizeChart\Contracts\SizeChart as SizeChartContract;

class SizeChart extends Model implements SizeChartContract
{
    protected $table = 'size_charts';

    protected $fillable = ['template_name', 'template_code', 'template_type', 'config_attribute', 'size_chart', 'image_path'];
}