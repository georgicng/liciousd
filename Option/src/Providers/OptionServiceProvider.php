<?php

namespace Gaiproject\Option\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class OptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'option');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views/admin', 'admin');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views/shop', 'shop');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('admin::layouts.style');
        });



        Event::listen('bagisto.admin.catalog.families.create.create_form_controls.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('admin::options.group.create');
        });
        Event::listen('catalog.attribute_family.create.after', 'Gaiproject\Option\Listeners\Catalog@createFamily');

        Event::listen('bagisto.admin.catalog.families.edit.edit_form_control.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('admin::options.group.edit');
        });
        Event::listen('catalog.attribute_family.update.after', 'Gaiproject\Option\Listeners\Catalog@editFamily');
        //Event::listen('catalog.product.create.after', 'Gaiproject\Option\Listeners\Catalog@createProduct');
        Event::listen('catalog.product.update.after', 'Gaiproject\Option\Listeners\Catalog@editProduct');
        Event::listen('catalog.product.delete.after', 'Gaiproject\Option\Listeners\Catalog@deleteProduct');

       /*  Event::listen('bagisto.shop.products.type.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('shop::products.view.types.optionable');
        }); */

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/product_types.php', 'product_types'
        );
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}
