<?php

namespace Gaiproject\Pickup\Providers;

use Illuminate\Support\ServiceProvider;

class PickupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       $this->registerConfig();
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/system.php', 'core');
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/carriers.php', 'carriers');
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


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        //$this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pickup');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views/admin', 'admin');

    }
}
