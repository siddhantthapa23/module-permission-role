<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Http\Requests\Role\StoreRequest;
use Modules\Administration\Http\Requests\Role\UpdateRequest;
use Modules\Administration\Exceptions\Role\CreateRoleErrorException;
use Modules\Administration\Exceptions\Role\UpdateRoleErrorException;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;

class RoleController extends Controller
{
    private $role;

    public function __construct(
        RoleRepository $role
    )
    {
        $this->role = $role;
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
}
