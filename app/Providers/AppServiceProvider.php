<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryEloquent;

/** Administration module repositories */
use Modules\Administration\Repositories\Module\ModuleRepository;
use Modules\Administration\Repositories\Module\ModuleRepositoryEloquent;
use Modules\Administration\Repositories\Permission\PermissionRepository;
use Modules\Administration\Repositories\Permission\PermissionRepositoryEloquent;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;
use Modules\Administration\Repositories\User\UserRepository;
use Modules\Administration\Repositories\User\UserRepositoryEloquent;
/** Administration module repositories */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'user' => \Modules\Administration\Entities\User::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            BaseRepository::class,
            BaseRepositoryEloquent::class
        );
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
}
