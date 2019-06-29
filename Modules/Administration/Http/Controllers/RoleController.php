<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Repositories\Role\RoleRepository;
use Modules\Administration\Http\Requests\Role\StoreRequest;
use Modules\Administration\Exceptions\Role\CreateRoleErrorException;

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
    public function show()
    {
        return view('administration::role.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('administration::role.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
