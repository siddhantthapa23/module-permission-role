<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Administration\Repositories\User\UserRepository;
use Modules\Administration\Http\Requests\User\StoreRequest;
use Modules\Administration\Http\Requests\User\UpdateRequest;
use Modules\Administration\Exceptions\User\CreateUserErrorException;
use Modules\Administration\Exceptions\User\UpdateUserErrorException;
use Modules\Administration\Repositories\User\UserRepositoryEloquent;

class UserController extends Controller
{
    private $user;

    public function __construct(
        UserRepository $user
    )
    {
        $this->user = $user;
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
}
