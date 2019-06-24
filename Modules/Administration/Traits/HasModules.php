<?php

namespace Modules\Administration\Traits;

use Spatie\Permission\Guard;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Modules\Administration\Contracts\Module;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Administration\Registrars\PermissionRegistrar;
use Modules\Administration\Exceptions\Module\ModuleDoesNotExist;
use Modules\Administration\Exceptions\Module\GuardDoesNotMatch;

trait HasModules
{
    private $moduleClass;

    public static function bootHasModules()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->Modules()->detach();
        });
    }

    public function getModuleClass()
    {
        if (! isset($this->moduleClass)) {
            $this->moduleClass = app(PermissionRegistrar::class)->getModuleClass();
        }

        return $this->moduleClass;
    }

    /**
     * A model may have multiple direct modules.
     */
    public function modules(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.module'),
            'model',
            config('permission.table_names.model_has_modules'),
            config('permission.column_names.model_morph_key'),
            'module_id'
        );
    }

    /**
     * Scope the model query to certain modules only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|\Modules\Administration\Contracts\Module|\Illuminate\Support\Collection $modules
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModule(Builder $query, $modules): Builder
    {
        $modules = $this->convertToModuleModels($modules);

        $rolesWithModules = array_unique(array_reduce($modules, function ($result, $module) {
            return array_merge($result, $module->roles->all());
        }, []));

        return $query->where(function ($query) use ($modules, $rolesWithModules) {
            $query->whereHas('modules', function ($query) use ($modules) {
                $query->where(function ($query) use ($modules) {
                    foreach ($modules as $module) {
                        $query->orWhere(config('permission.table_names.modules').'.id', $module->id);
                    }
                });
            });
            if (count($rolesWithModules) > 0) {
                $query->orWhereHas('roles', function ($query) use ($rolesWithModules) {
                    $query->where(function ($query) use ($rolesWithModules) {
                        foreach ($rolesWithModules as $role) {
                            $query->orWhere(config('permission.table_names.roles').'.id', $role->id);
                        }
                    });
                });
            }
        });
    }

    /**
     * @param string|array|\Modules\Administration\Contracts\Module|\Illuminate\Support\Collection $modules
     *
     * @return array
     */
    protected function convertToModuleModels($modules): array
    {
        if ($modules instanceof Collection) {
            $modules = $modules->all();
        }

        $modules = is_array($modules) ? $modules : [$modules];

        return array_map(function ($module) {
            if ($module instanceof Module) {
                return $module;
            }

            return $this->getModuleClass()->findByName($module, $this->mGetDefaultGuardName());
        }, $modules);
    }

    /**
     * Determine if the model may perform the given module.
     *
     * @param string|int|\Modules\Administration\Contracts\Module $module
     * @param string|null $guardName
     *
     * @return bool
     * @throws ModuleDoesNotExist
     */
    public function hasModule($module, $guardName = null): bool
    {
        $moduleClass = $this->getModuleClass();

        if (is_string($module)) {
            $module = $moduleClass->findByName(
                $module,
                $guardName ?? $this->mGetDefaultGuardName()
            );
        }

        if (is_int($module)) {
            $module = $moduleClass->findById(
                $module,
                $guardName ?? $this->mGetDefaultGuardName()
            );
        }

        if (! $module instanceof Module) {
            throw new ModuleDoesNotExist;
        }

        return $this->hasDirectModule($module) || $this->hasModuleViaRole($module);
    }

    /**
     * @deprecated since 2.35.0
     * @alias of hasModule()
     */
    public function hasUncachedModule($module, $guardName = null): bool
    {
        return $this->hasModule($module, $guardName);
    }

    /**
     * An alias to hasModule(), but avoids throwing an exception.
     *
     * @param string|int|\Modules\Administration\Contracts\Module $module
     * @param string|null $guardName
     *
     * @return bool
     */
    public function checkModule($module, $guardName = null): bool
    {
        try {
            return $this->hasModule($module, $guardName);
        } catch (ModuleDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Determine if the model has any of the given modules.
     *
     * @param array ...$modules
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAnyModule(...$modules): bool
    {
        if (is_array($modules[0])) {
            $modules = $modules[0];
        }

        foreach ($modules as $module) {
            if ($this->checkModule($module)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given modules.
     *
     * @param array ...$modules
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllModules(...$modules): bool
    {
        if (is_array($modules[0])) {
            $modules = $modules[0];
        }

        foreach ($modules as $module) {
            if (! $this->hasModule($module)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has, via roles, the given module.
     *
     * @param \Modules\Administration\Contracts\Module $module
     *
     * @return bool
     */
    protected function hasModuleViaRole(Module $module): bool
    {
        return $this->hasRole($module->roles);
    }
    
    /**
     * Determine if the model has the given module.
     *
     * @param string|int|\Modules\Administration\Contracts\Module $module
     *
     * @return bool
     */
    public function hasDirectModule($module): bool
    {
        $moduleClass = $this->getmoduleClass();

        if (is_string($module)) {
            $module = $moduleClass->findByName($module, $this->mGetDefaultGuardName());
            if (! $module) {
                return false;
            }
        }

        if (is_int($module)) {
            $module = $moduleClass->findById($module, $this->mGetDefaultGuardName());
            if (! $module) {
                return false;
            }
        }

        if (! $module instanceof Module) {
            return false;
        }

        return $this->modules->contains('id', $module->id);
    }

    /**
     * Return all the modules the model has via roles.
     */
    public function getModulesViaRoles(): Collection
    {
        return $this->load('roles', 'roles.modules')
            ->roles->flatMap(function ($role) {
                return $role->modules;
            })->sort()->values();
    }

    /**
     * Return all the modules the model has, both directly and via roles.
     *
     * @throws \Exception
     */
    public function getAllModules(): Collection
    {
        $modules = $this->modules;

        if ($this->roles) {
            $modules = $modules->merge($this->getModulesViaRoles());
        }

        return $modules->sort()->values();
    }

    /**
     * Grant the given module(s) to a role.
     *
     * @param string|array|\Modules\Administration\Contracts\Module|\Illuminate\Support\Collection $modules
     *
     * @return $this
     */
    public function assignModule(...$modules)
    {
        $modules = collect($modules)
            ->flatten()
            ->map(function ($module) {
                if (empty($module)) {
                    return false;
                }

                return $this->getStoredModule($module);
            })
            ->filter(function ($module) {
                return $module instanceof Module;
            })
            ->each(function ($module) {
                $this->mEnsureModelSharesGuard($module);
            })
            ->map->id
            ->all();

        $model = $this->getModel();

        if ($model->exists) {
            $this->modules()->sync($modules, false);
            $model->load('modules');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($modules, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->modules()->sync($modules, false);
                    $object->load('modules');
                    $modelLastFiredOn = $object;
                }
            );
        }

        $this->forgetCachedModules();

        return $this;
    }

    /**
     * Remove all current modules and set the given ones.
     *
     * @param string|array|\Modules\Administration\Contracts\Module|\Illuminate\Support\Collection $modules
     *
     * @return $this
     */
    public function syncModules(...$modules)
    {
        $this->modules()->detach();

        return $this->assignModule($modules);
    }

    /**
     * Remove the given module.
     *
     * @param \Modules\Administration\Contracts\Module|\Modules\Administration\Contracts\Module[]|string|string[] $module
     *
     * @return $this
     */
    public function removeModule($module)
    {
        $this->modules()->detach($this->getStoredModule($module));

        $this->forgetCachedModules();

        $this->load('modules');

        return $this;
    }

    public function getModuleNames(): Collection
    {
        return $this->modules->pluck('name');
    }

    /**
     * @param string|array|\Modules\Administration\Contracts\Module|\Illuminate\Support\Collection $modules
     *
     * @return \Modules\Administration\Contracts\Module|\Modules\Administration\Contracts\Module[]|\Illuminate\Support\Collection
     */
    protected function getStoredModule($modules)
    {
        $moduleClass = $this->getModuleClass();

        if (is_numeric($modules)) {
            return $moduleClass->findById($modules, $this->mGetDefaultGuardName());
        }

        if (is_string($modules)) {
            return $moduleClass->findByName($modules, $this->mGetDefaultGuardName());
        }

        if (is_array($modules)) {
            return $moduleClass
                ->whereIn('name', $modules)
                ->whereIn('guard_name', $this->mGetGuardNames())
                ->get();
        }

        return $modules;
    }

    /**
     * @param \Modules\Administration\Contracts\Module|\Spatie\Permission\Contracts\Role $roleOrModule
     *
     * @throws \Modules\Administration\Exceptions\Module\GuardDoesNotMatch
     */
    protected function mEnsureModelSharesGuard($roleOrModule)
    {
        if (! $this->mGetGuardNames()->contains($roleOrModule->guard_name)) {
            throw GuardDoesNotMatch::create($roleOrModule->guard_name, $this->mGetGuardNames());
        }
    }

    protected function mGetGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function mGetDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    /**
     * Forget the cached modules.
     */
    public function forgetCachedModules()
    {
        app(PermissionRegistrar::class)->forgetCachedModules();
    }
}