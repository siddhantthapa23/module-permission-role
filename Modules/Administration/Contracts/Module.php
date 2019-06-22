<?php

namespace Modules\Administration\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Module 
{
    /**
     * A module can be applied to roles.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Find a module by its name and guard name.
     * 
     * @param string $name
     * @param string|null $guardName
     * @return \Modules\Administration\Contracts\Module
     * @throws \Modules\Administration\Exceptions\Module\ModuleDoesNotExist
     */
    public static function findByName(string $name, $guardName): self;

    /**
     * Find a module by its id and guard name.
     * 
     * @param int $id
     * @param string|null $guardName
     * @return \Modules\Administration\Contracts\Module
     * @throws \Modules\Administration\Exceptions\Module\ModuleDoesNotExist
     */
    public static function findById(int $id, $guardName): self;

    /**
     * Find or Create a module by its name and guard name.
     * 
     * @param string $name
     * @param string|null $guardName
     * @return \Modules\Administration\Contracts\Module
     */
    public static function findOrCreate(string $name, $guardName): self;
}