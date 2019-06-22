<?php

namespace Modules\Administration\Entities;

use Spatie\Permission\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Administration\Contracts\Module as ModuleContract;
use Modules\Administration\Exceptions\Module\ModuleAlreadyExists;
use Modules\Administration\Exceptions\Module\ModuleDoesNotExist;

class Module extends Model implements ModuleContract
{
    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->setTable(config('permission.table_names.modules'));
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $module = static::getModules(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']])->first();

        if($module) {
            throw ModuleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        if(isNptLumen() && app()::VERSION < '5.4') {
            return parent::create($attributes);
        }

        return static::query()->create($attributes);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_modules'),
            'module_id',
            'role_id'
        );
    }

    public static function findByName(string $name, $guardName = null): ModuleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $module = static::getModules(['name' => $name, 'guard_name' => $guardName])->first();
        if(! $module) {
            throw ModuleDoesNotExist::create($name, $guardName);
        }

        return $module;
    }

    public static function findById(int $id, $guardName = null): ModuleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $module = static::getModules(['id' => $id, 'guard_name' => $guardName])->first();

        if (! $module) {
            throw ModuleDoesNotExist::withId($id, $guardName);
        }

        return $module;
    }

    public static function findOrCreate(string $name, $guardName = null): ModuleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $module = static::getModules(['name' => $name, 'guard_name' => $guardName])->first();

        if (! $module) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $module;
    }

    /**
     * Get the current cached modules.
     */
    protected static function getModule(array $params = [])
    {
        // need to work on body part.
    }
}
