<?php

namespace Gaiproject\Theme\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Tree;
use Gaiproject\Theme\Http\Middleware\AuthenticateCustomer;
use Gaiproject\Theme\Http\Middleware\Currency;
use Gaiproject\Theme\Http\Middleware\Locale;
use Gaiproject\Theme\Http\Middleware\Theme;

class ThemeServiceProvider extends ServiceProvider
{
   /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        /* loaders */
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        Route::middleware('web')->group(__DIR__.'/../Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'licious');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'licious');

        /* aliases */
        $router->aliasMiddleware('currency', Currency::class);
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('customer', AuthenticateCustomer::class);
        $router->aliasMiddleware('theme', Theme::class);

        $this->publishes([
            dirname(__DIR__).'/Config/imagecache.php' => config_path('imagecache.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('themes/licious/views'),

        ]);


        /* View Composers */
        $this->composeView();

        /* Paginator */
        Paginator::defaultView('licious::partials.pagination');
        Paginator::defaultSimpleView('licious::partials.pagination');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'licious');

        /* Breadcrumbs */
        require __DIR__.'/../Routes/breadcrumbs.php';

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Bind the the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
            $tree = Tree::create();

            foreach (config('menu.customer') as $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.customer'
        );
    }
}
