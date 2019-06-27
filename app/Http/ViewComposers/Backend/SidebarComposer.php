<?php

namespace App\Http\ViewComposers\Backend;

use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Bind data to the View
     * 
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        if(session()->has('accessModules')) {
            $view->with('modules', session()->get('accessModules'));
        }

        $modules = auth()->user()->getAllModules()->toArray();
        $accessModules = [];
        $parentCount = $childCount = 0;
        foreach ($modules as $module) {
            if($module['parent_id'] == 0 && ! in_array($module, $accessModules)) {
                /** store parent module and parent count in temporary variable */
                $tempParent = $module;
                $tempParentCount = $parentCount;
                /** store parent module and parent count in temporary variable end */
                
                $accessModules[$parentCount] = $module;
                $parentCount++;
            } else {
                if($tempParent['id'] == $module['parent_id']) { // if true then module must be child
                    $accessModules[$tempParentCount]['childrens'][$childCount] = $module;
                    $childCount++;
                }
            }
        }

        session()->put('accessModules', $accessModules);
        $view->with('modules', $accessModules);
    }
}