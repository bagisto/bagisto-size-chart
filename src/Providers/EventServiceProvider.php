<?php

namespace Webkul\SizeChart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.catalog.product.edit_form_accordian.additional_views.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('sizechart::admin.catelog.products.sizechart');
        });

        Event::listen('bagisto.shop.products.view.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('sizechart::shop.products.sizechart');
        });

        Event::listen('bagisto.shop.products.view.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('sizechart::shop.products.modal');
        });
        
    }
}