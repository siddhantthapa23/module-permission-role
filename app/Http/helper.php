<?php

/**
 * Filter module based on passed permissions.
 * 
 * @param array $modules
 * @param array $permissions
 * @return array
 */
if(! function_exists('filter_modules')) {
    function filter_modules(array $modules, array $permissions): array
    {
        $filteredModuleArr = array();
        foreach ($modules as $module) { // loop through modules
            foreach ($permissions as $permission) {  // loop through permissions
                if($module['parent_id'] == 0 && !in_array($module, $filteredModuleArr)) { // check if parent module
                    $tempParent = $module;
                }
                if(stripos($module['name'], $permission['group_name']) !== false) {
                    if($tempParent['id'] == $module['parent_id'] && !in_array($tempParent, $filteredModuleArr)) {
                        array_push($filteredModuleArr, $tempParent);
                    }
                    array_push($filteredModuleArr, $module);
                }
            }
        }

        return $filteredModuleArr;
    }
}