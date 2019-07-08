<?php

namespace Modules\Administration\Traits;

use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait ExtendedHasRoles
{
    use SpatieHasRoles, ExtendedHasPermissions;
}