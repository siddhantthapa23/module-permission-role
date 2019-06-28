<?php

namespace Modules\Administration\Repositories\Module;

use App\Repositories\BaseRepositoryEloquent;
use Modules\Administration\Entities\Module;

class ModuleRepositoryEloquent extends BaseRepositoryEloquent implements ModuleRepository
{
    /**
     * ModuleRepositoryEloquent constructor
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->model = $module;
    }
}