<?php

namespace Modules\Administration\Exceptions\Module;

use InvalidArgumentException;

class ModuleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $moduleName, string $guardName)
    {
        return new static("A `{$moduleName}` module already exists for guard `{$guardName}`.");
    }
}