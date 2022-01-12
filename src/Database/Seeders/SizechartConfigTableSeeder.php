<?php

namespace Webkul\SizeChart\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizechartConfigTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('core_config')->insert(
            [
                'code'         => 'catalog.products.size-chart.enable-sizechart',
                'value'         => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]
        );

    }
}
