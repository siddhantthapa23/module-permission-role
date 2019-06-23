<?php

namespace Modules\Administration\Registrars;

use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Collection;
use Illuminate\Cache\CacheManager;
use Spatie\Permission\PermissionRegistrar as SpatiePermissionRegistrar;

class PermissionRegistrar extends SpatiePermissionRegistrar
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;

    /** @var \Illuminate\Cache\CacheManager */
    protected $cacheManager;

    /** @var string */
    protected $moduleClass;

    /** @var \Illuminate\Support\Collection */
    protected $modules;

    /**
     * PermissionRegistrar constructor.
     * 
     * @param \Illuminate\Auth\Access\Gate $gate
     * @param \Illuminate\Cache\CacheManager $cacheManager
     */
    public function __construct(Gate $gate, CacheManager $cacheManager)
    {
        parent::__construct($gate, $cacheManager);

        $this->moduleClass = config('permission.models.module');
    }

    /**
     * Get an instance of module class.
     * 
     * @return \Modules\Administration\Contracts\Module
     */
    public function getModuleClass(): Module
    {
        return app($this->moduleClass);
    }

    /**
     * Get the modules based on the passed params.
     * 
     * @param array $params
     * @return \Illuminate\Support\Collection
     */
    public function getModules(array $params = []): Collection
    {
        if($this->modules === null) {
            $this->modules = $this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function() {
                return $this->getModuleClass()
                    ->with('roles')
                    ->get();
            });
        }

        $modules = clone $this->modules;

        foreach ($params as $attr => $value) {
            $modules = $modules->where($attr, $value);
        }

        return $modules;
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedModules()
    {
        $this->modules = null;
        $this->cache->forget(self::$cacheKey);
    }
}