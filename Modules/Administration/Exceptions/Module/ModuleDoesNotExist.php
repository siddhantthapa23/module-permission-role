<?php

namespace Modules\Administration\Exceptions\Module;

use InvalidArgumentException;

class ModuleDoesNotExist extends InvalidArgumentException
{
    public static function create(string $moduleName, string $guardName = '')
    {
        return new static("There is no module named `{$moduleName}` for guard `{$guardName}`.");
    }

    public static function withId(int $moduleId)
    {
        return new static("There is no [module] with id `{$moduleId}`.");
    }
}