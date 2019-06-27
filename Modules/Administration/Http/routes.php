<?php

Route::group(['middleware' => 'web', 'prefix' => 'administration', 'as' => 'administration.', 'namespace' => 'Modules\Administration\Http\Controllers'], function()
{
    /** user routes with permission middleware */
    Route::group(['middleware' => 'permission:view user'], function(){
        /** user CRUD routes */
        Route::get('users', 'UserController@index')->name('users.index');
        Route::get('users/create', 'UserController@create')->name('users.create')->middleware('permission:create user');
        Route::post('users', 'UserController@store')->name('users.store');
        Route::get('users/{user}', 'UserController@show')->name('users.show');
        Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('permission:edit user');
        Route::put('users/{user}', 'UserController@update')->name('users.update');
        Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('permission:delete user');
        Route::put('users/{user}/change/status', 'UserController@changeStatus');
        /** user CRUD routes end */

        /** user attach role routes */
        Route::get('users/{id}/attach-role', 'UserController@attachRoleView')->name('users.attach-role')->middleware('role:admin');
        Route::post('users/{id}/attach-role', 'UserController@storeRoles')->name('users.attach-role.store');
        Route::Delete('users/{userId}/roles/{roleName}', 'UserController@removeRole')->middleware('role:admin');
        /** user attach role routes end */

        /** user attach permission routes */
        Route::get('users/{id}/attach-permission', 'UserController@attachPermissionView')->name('users.attach-permission')->middleware('role:admin');
        Route::post('users/{id}/attach-permission', 'UserController@storePermissions')->name('users.attach-permission.store');
        Route::Delete('users/{userId}/permissions/{permissionId}', 'UserController@removePermissionAndModule')->middleware('role:admin');
        /** user attach permission routes end */
    });
    /** user routes with permission middleware end */

    /** role routes with permission middleware */
    Route::group(['middleware' => 'permission:view role'], function(){
        /** role CRUD routes */
        Route::get('roles', 'RoleController@index')->name('roles.index');
        Route::get('roles/create', 'RoleController@create')->name('roles.create')->middleware('permission:create role');
        Route::post('roles', 'RoleController@store')->name('roles.store');
        Route::get('roles/{role}', 'RoleController@show')->name('roles.show');
        Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('permission:edit role');
        Route::put('roles/{role}', 'RoleController@update')->name('roles.update');
        Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy')->middleware('permission:delete role');
        /** role CRUD routes end */

        /** role attach permission routes */
        Route::get('roles/{id}/attach-permission', 'RoleController@attachPermissionView')->name('roles.attach-permission')->middleware('role:admin');
        Route::post('roles/{id}/attach-permission', 'RoleController@storePermissions')->name('roles.attach-permission.store');
        Route::Delete('roles/{roleId}/permissions/{permissionId}', 'RoleController@removePermission')->middleware('role:admin');
        /** role attach permission routes */
    });
    /** role routes with permission middleware end */
});
