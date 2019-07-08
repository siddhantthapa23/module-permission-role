<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Repositories\User\UserRepository;
use Modules\Administration\Repositories\Module\ModuleRepository;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Repositories\Permission\PermissionRepository;
use Modules\Administration\Http\Requests\User\StoreRequest;
use Modules\Administration\Http\Requests\User\UpdateRequest;
use Modules\Administration\Http\Requests\User\RoleStoreRequest;
use Modules\Administration\Http\Requests\User\PermissionStoreRequest;
use Modules\Administration\Exceptions\User\CreateUserErrorException;
use Modules\Administration\Exceptions\User\UpdateUserErrorException;
use Modules\Administration\Repositories\User\UserRepositoryEloquent;

class UserController extends Controller
{
    private $user;

    private $module;

    private $role;

    private $permission;

    public function __construct(
        UserRepository $user,
        ModuleRepository $module,
        RoleRepository $role,
        PermissionRepository $permission
    )
    {
        $this->user = $user;
        $this->module = $module;
        $this->role = $role;
        $this->permission = $permission;
        $this->destinationPath = 'uploads/administration/users/';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('administration::user.index')
            ->withUsers($this->user->all());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('administration::user.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  StoreRequest $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->except('_token', 'avatar');

            $imageFile = $request->avatar;
            if ($imageFile) {
                $extension = strrchr($imageFile->getClientOriginalName(), '.');
                $new_file_name = "avatar_" . $request->first_name . time();
                $avatar = $imageFile->move($this->destinationPath, $new_file_name.$extension);
                $data['avatar'] = isset($avatar) ? $new_file_name . $extension : null;
            }
            
            $this->user->createUser($data);
            return redirect()->route('administration.users.index')
                    ->withSuccessMessage('User has been created successfully.');
        } catch (CreateUserErrorException $e) { // need to work on it.
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
        $user = $this->user->findUser($id);
        return view('administration::user.show')
            ->withUser($user)
            ->withRoles($user->roles()->get())
            ->withPermissions($user->getDirectPermissions());
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        return view('administration::user.edit')
            ->withUser($this->user->findUser($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateRequest $request
     * @return Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = $request->except('avatar');

            $user = $this->user->findUser($id);
            $previousAvatar = $user->avatar;

            $imageFile = $request->avatar;
            if ($imageFile) {
                if (file_exists($this->destinationPath . $previousAvatar) && $previousAvatar != '') {
                    unlink($this->destinationPath . $previousAvatar);
                }
                $extension = strrchr($imageFile->getClientOriginalName(), '.');
                $new_file_name = "avatar_" . $request->first_name . time();
                $avatar = $imageFile->move($this->destinationPath, $new_file_name.$extension);
                $data['avatar'] = isset($avatar) ? $new_file_name . $extension : null;
            }

            $data['password'] = ($request->password) ? bcrypt($request->password) : $user->password;
            
            $userRepo = new UserRepositoryEloquent($user);
            $user = $userRepo->updateUser($data);

            return redirect()->route('administration.users.index')
                ->withSuccessMessage('User has been updated successfully.');
        } catch (UpdateUserErrorException $e) {
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
        $user = $this->user->findUser($id);

        if($user->id == auth()->user()->id) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Sorry, you are not allowed to delete admin.'
            ], 200);
        }

        $user->syncRoles([]); // All current roles will be removed from the user and replaced by the array given.
        $user->syncPermissions([]); // All current permissions will be removed from the user and replaced by the array given.
        $user->syncModules([]); // All current modules will be removed from the user and replaced by the array given.

        $userRepo = new UserRepositoryEloquent($user);
        $flag = $userRepo->deleteUser();
        if($flag) {
            return response()->json([
                'type' => 'Success',
                'message' => 'User has been deleted successfully.'
            ], 200);
        }
        return response()->json([
            'type' => 'Error',
            'message' => 'Internal serve error, please try again later.'
        ], 200);
    }

    /**
     * Display attach role view.
     * @param int $id
     * @return Response
     */
    public function attachRoleView(int $id)
    {
        return view('administration::user.attach_role')
            ->withUserId($id)
            ->withRoles($this->role->latest());
    }

    /**
     * @param RoleStoreRequest $request
     * @param int $id
     * @return Reponse
     */
    public function storeRoles(RoleStoreRequest $request, int $id)
    {
        $user = $this->user->findUser($id);
        
        if($user->assignRole($request->roles)) {
            return redirect()->route('administration.users.show', $id)
                ->withSuccessMessage('Role has been attached successfully.');
        }
        return redirect()->back()->withInput()
            ->withErrorMessage('Internal server error, please try again later.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $userId
     * @param string $roleName
     * @return Response
     */
    public function removeRole(int $userId, string $roleName)
    {
        $user = $this->user->findUser($userId);
        $remove = $user->removeRole($roleName);

        if($remove || is_null($remove)) {
            return response()->json([
                'type' => 'Success',
                'message' => 'Role has been removed successfully.'
            ], 200);
        }
        return response()->json([
            'type' => 'Error',
            'message' => 'Internal serve error, please try again later.'
        ], 200);
    }

    /**
     * Display attach permission view.
     * @param int $id
     * @return Response
     */
    public function attachPermissionView($id)
    {
        return view('administration::user.attach_permission')
            ->withUser($this->user->findUser($id))
            ->withModules($this->module->withHierarchy())
            ->withPermissions($this->permission->groupByGuardName());
    }

    /**
     * @param PermissionStoreRequest $request
     * @param int $id
     * @return Reponse
     */
    public function storePermissions(PermissionStoreRequest $request, int $id)
    {
        $user = $this->user->findUser($id);

        /** get match permission array. */
        $permissionArr = array_map(function ($permissionId) use ($user){
            return $user->getMatchPermissions($permissionId);
        }, $request->permissions);

        $user = $user->syncPermissions($permissionArr);

        $permissions = $user->getDirectPermissions()->toArray();
        /** search for 'view' string in permission name and get match permission array. */
        $matchPermissionArr = array_filter($permissions, function($permission) {
            return strpos($permission['name'], 'view') !== false;
        });

        if(count($matchPermissionArr) > 0){
            $filteredModuleArr = filter_modules($this->module->all()->toArray(), $matchPermissionArr);
            $user = $user->syncModules($filteredModuleArr);
        } else {
            $user = $user->syncModules([]); // reset all modules for user.
        }

        if($user) {
            return redirect()->route('administration.users.show', $id)
                ->withSuccessMessage('Permissions has been attached successfully.');
        }
        return redirect()->back()->withInput()
            ->withWarningMessage('Internal server error, please try again later.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $userId
     * @param int $permissionId
     * @return Response
     */
    public function removePermissionAndModule(int $userId, int $permissionId)
    {
        $user = $this->user->findUser($userId);
        $permission = $this->permission->find($permissionId);
        $user = $user->revokePermissionTo($permission);
        
        $permissions = $user->getDirectPermissions()->toArray();

        /** search for 'view' string in permission name and get match permission array. */
        $matchPermissionArr = array_filter($permissions, function($permission) {
            return strpos($permission['name'], 'view') !== false;
        });

        if(count($matchPermissionArr) > 0){
            $filteredModuleArr = $this->filterModule($matchPermissionArr);
            $user = $user->syncModules($filteredModuleArr);
        } else {
            $user = $user->syncModules([]); // reset all modules for user.
        }

        if($user) {
            return response()->json([
                'type' => 'Success',
                'message' => 'Permission has been removed successfully.'
            ], 200);
        }
        return response()->json([
            'type' => 'Error',
            'message' => 'Internal serve error, please try again later.'
        ], 200);
    }

    /**
     * Change status of the specified user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $user = $this->user->changeStatus($request->id, $request->status);
        if ($user) {
            return response()->json([
                'type' => 'Success',
                'message'=> 'Status has been changed successfully.'
            ], 200);
        }
        return response()->json([
            'type'=>'Error',
            'message'=>'Internal server error, please try again later.',
        ], 200);
    }
}
