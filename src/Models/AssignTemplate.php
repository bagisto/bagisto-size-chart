<?php

namespace Webkul\SizeChart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\SizeChart\Contracts\AssignTemplate as AssignTemplateContract;

class AssignTemplate extends Model implements AssignTemplateContract
{
    protected $table = 'template_assign';

    protected $fillable = ['product_id', 'template_id'];
}