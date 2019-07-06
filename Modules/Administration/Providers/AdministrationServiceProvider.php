<?php

namespace Modules\Administration\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Administration\Repositories\Module\ModuleRepository;
use Modules\Administration\Repositories\Module\ModuleRepositoryEloquent;
use Modules\Administration\Repositories\Permission\PermissionRepository;
use Modules\Administration\Repositories\Permission\PermissionRepositoryEloquent;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;
use Modules\Administration\Repositories\User\UserRepository;
use Modules\Administration\Repositories\User\UserRepositoryEloquent;
use Illuminate\Database\Eloquent\Relations\Relation;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Relation::morphMap([
            'user' => \Modules\Administration\Entities\User::class
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            ModuleRepository::class,
            ModuleRepositoryEloquent::class
        );
        $this->app->singleton(
            PermissionRepository::class,
            PermissionRepositoryEloquent::class
        );
        $this->app->singleton(
            RoleRepository::class,
            RoleRepositoryEloquent::class
        );
        $this->app->singleton(
            UserRepository::class,
            UserRepositoryEloquent::class
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('administration.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'administration'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/administration');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/administration';
        }, \Config::get('view.paths')), [$sourcePath]), 'administration');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/administration');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'administration');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'administration');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
