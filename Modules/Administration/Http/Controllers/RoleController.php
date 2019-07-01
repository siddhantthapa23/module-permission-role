<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Repositories\Module\ModuleRepository;
use Modules\Administration\Repositories\Permission\PermissionRepository;
use Modules\Administration\Http\Requests\Role\StoreRequest;
use Modules\Administration\Http\Requests\Role\UpdateRequest;
use Modules\Administration\Http\Requests\Role\PermissionStoreRequest;
use Modules\Administration\Exceptions\Role\CreateRoleErrorException;
use Modules\Administration\Exceptions\Role\UpdateRoleErrorException;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;

class RoleController extends Controller
{
    private $role;

    private $module;

    private $permission;

    public function __construct(
        RoleRepository $role,
        ModuleRepository $module,
        PermissionRepository $permission
    )
    {
        $this->role = $role;
        $this->module = $module;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('administration::role.index')
            ->withRoles($this->role->all());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('administration::role.create');
    }

    /**
     * @param  StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->except('_token');

            $this->role->createRole($data);
            return redirect()->route('administration.roles.index')
                ->withSuccessMessage('Role has been created successfully.');
        } catch (CreateRoleErrorException $e) {
            return redirect()->back()->withInput()
                ->withErrorMessage($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $role = $this->role->findRole($id);
        return view('administration::role.show')
            ->withRole($role)
            ->withPermissions($role->getAllPermissions());
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        return view('administration::role.edit')
            ->withRole($this->role->findRole($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->except('_token');

            $role = $this->role->findRole($id);
            $roleRepo = new RoleRepositoryEloquent($role);
            $roleRepo->updateRole($data);

            return redirect()->route('administration.roles.index')
                ->withSuccessMessage('Role has been created successfully.');
        } catch (UpdateRoleErrorException $e) {
            return redirect()->back()->withInput()
                ->withErrorMessage($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $role = $this->role->findRole($id);

            if($role->name == 'admin') {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Sorry, you are not allowed to delete admin role.'
                ], 200);
            }
    
            $roleRepo = new RoleRepositoryEloquent($role);
            $roleRepo->deleteRole();

            return response()->json([
                'type' => 'success',
                'message' => 'Role has been deleted successfully.'
            ], 200);
        } catch (\PDOException $e) {
             return response()->json([
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Display attach permission view.
     * @param int $id
     * @return Response
     */
    public function attachPermissionView($id)
    {
        return view('administration::role.attach_permission')
            ->withRole($this->role->findRole($id))
            ->withModules($this->module->withHierarchy())
            ->withPermissions($this->permission->groupByGuardName());
    }

    /**
     * @param \Modules\Administration\Http\Requests\Role\PermissionStoreRequest $request
     * @param int $id
     * @return Reponse
     */
    public function storePermissions(PermissionStoreRequest $request, int $id)
    {
        $role = $this->role->findRole($id);

        /** get match permission array. */
        $permissionArr = array_map(function ($permissionId) use ($role){
            return $role->getMatchPermissions($permissionId);
        }, $request->permissions);

        $role = $role->syncPermissions($permissionArr);

        $permissions = $role->getAllPermissions()->toArray();
        /** search for 'view' string in permission name and get match permission array. */
        $matchPermissionArr = array_filter($permissions, function($permission) {
            return strpos($permission['name'], 'view') !== false;
        });

        if(count($matchPermissionArr) > 0) {
            $filteredModuleArr = filter_modules($this->module->all()->toArray(), $matchPermissionArr);
            $role = $role->syncModules($filteredModuleArr);
        } else {
            $role = $role->syncModules([]); // reset all modules for role.
        }

        if($role) {
            return redirect()->route('administration.roles.show', $id)
                ->withSuccessMessage('Permission has been attached successfully.');
        }
        return redirect()->back()->withInput()
            ->withErrorMessage('Internal server error, Please try again later.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $roleId
     * @param int $permissionId
     * @return Response
     */
    public function removePermission(int $roleId, int $permissionId)
    {
        $role = $this->role->findRole($roleId);
        $permission = $this->permission->find($permissionId);
        $role = $role->revokePermissionTo($permission);
        
        $permissions = $role->getAllPermissions()->toArray();

        /** search for 'view' string in permission name and get match permission array. */
        $matchPermissionArr = array_filter($permissions, function($permission) {
            return strpos($permission['name'], 'view') !== false;
        });

        if(count($matchPermissionArr) > 0) {
            $filteredModuleArr = filter_modules($this->module->all()->toArray(), $matchPermissionArr);
            $role = $role->syncModules($filteredModuleArr);
        } else {
            $role = $role->syncModules([]); // reset all modules for user.
        }

        if($role) {
            return response()->json([
                'type' => 'success',
                'message' => 'Permission has been removed successfully.'
            ], 200);
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Internal serve error, please try again later.'
        ], 200);
    }
}
