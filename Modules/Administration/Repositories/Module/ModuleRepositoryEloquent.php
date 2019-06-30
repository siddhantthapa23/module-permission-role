<?php

namespace Modules\Administration\Repositories\Module;

use Illuminate\Support\Collection;
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

    /**
     * Get modules with hierarchy.
     * 
     * @return Collection
     */
    public function withHierarchy() : Collection
    {
        return $this->model->with(['childrens' => function($child){
                    $child->where('is_active', '1');
                }], 'childrens.childrens')
                ->where('parent_id', 0)
                ->where('is_active', '1')
                ->orderBy('order_position')
                ->get();
    }
}