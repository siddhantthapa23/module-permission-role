<?php

namespace Modules\Administration\Exceptions\Module;

use InvalidArgumentException;
use Illuminate\Support\Collection;

class GuardDoesNotMatch extends InvalidArgumentException
{
    public static function create(string $givenGuard, Collection $expectedGuards) 
    {
        return new static("The given role or module should use guard `{$expectedGuards->implode(', ')}` instead of `{$givenGuard}`.");
    }
}