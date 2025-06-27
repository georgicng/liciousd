<?php

namespace Gaiproject\Theme\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\AnonymousComponent;
use Webkul\Core\Tree;
use Gaiproject\Theme\Http\Middleware\AuthenticateCustomer;
use Gaiproject\Theme\Http\Middleware\Currency;
use Gaiproject\Theme\Http\Middleware\Locale;
use Gaiproject\Theme\Http\Middleware\Theme;
use Gaiproject\Theme\Service\ShortcodeService;


class ThemeServiceProvider extends ServiceProvider
{
    protected $shortCodeService;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        /* loaders */
        Route::middleware('web')->group(__DIR__ . '/../Routes/web.php');
        Route::middleware('web')->group(__DIR__ . '/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'licious');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'licious');
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        /* aliases */
        $router->aliasMiddleware('currency', Currency::class);
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('customer', AuthenticateCustomer::class);
        $router->aliasMiddleware('theme', Theme::class);

        $this->publishes([
            dirname(__DIR__) . '/Config/imagecache.php' => config_path('imagecache.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('themes/licious/views'),

        ]);


        /* View Composers */
        $this->composeView();

        /* Paginator */
        Paginator::defaultView('licious::partials.pagination');
        Paginator::defaultSimpleView('licious::partials.pagination');

        Blade::anonymousComponentPath(__DIR__ . '/../Resources/views/components', 'licious');

        /* Breadcrumbs */
        require __DIR__ . '/../Routes/breadcrumbs.php';

        $this->app->register(EventServiceProvider::class);
        $this->app->singleton('shortcode', function () {
            return $this->shortCodeService;
        });
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
            dirname(__DIR__) . '/Config/menu.php',
            'menu.customer'
        );
        $this->shortCodeService = new ShortcodeService();
        $this->shortCodeService->register('category_carousel', function ($data) {
            return Blade::renderComponent(
                new AnonymousComponent(
                    view('licious::components.categories.carousel'),
                    [
                        'title' => $data['name'] ?? '',
                        'src' => route('shop.api.categories.index', $data['filters'] ?? []),
                        'navigation-link' => route('shop.home.index')
                    ]
                )
            );
        });
        $this->shortCodeService->register('categories', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.categories'), $data));
        });
        $this->shortCodeService->register('deals', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.deals'), $data));
        });
        $this->shortCodeService->register('hero_slider', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.hero-slider'), $data));
        });
        $this->shortCodeService->register('instagram', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.instagram'), $data));
        });
        $this->shortCodeService->register('services', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.services'), $data));
        });
        $this->shortCodeService->register('new_product', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.new-product'), $data));
        });
        $this->shortCodeService->register('popular_product', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.popular-product'), $data));
        });
        $this->shortCodeService->register('popular_products', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.popular-products'), $data));
        });
        $this->shortCodeService->register('product_banner', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.product-banner'), $data));
        });
        $this->shortCodeService->register('testimonials', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.testimonials'), $data));
        });
        $this->shortCodeService->register('top_collection', function ($data) {
            return Blade::renderComponent(new AnonymousComponent(view('licious::components.shortcodes.top-collection'), $data));
        });
    }
}
