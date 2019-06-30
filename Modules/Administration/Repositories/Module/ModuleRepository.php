<?php

namespace Modules\Administration\Repositories\Module;

use Illuminate\Support\Collection;

interface ModuleRepository 
{
    public function withHierarchy() : Collection;
}