<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDeleteRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserAddRequest;

class ManageUsersController extends Controller
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function manage() {
        return view('admin\manage_users')->with('users', $this->userRepository->getAll());
    }

    public function delete(UserDeleteRequest $request) {
        if($request->has('userId')) {
            $this->userRepository->destroy($request->input('userId'));
        }
        return redirect()->back();
    }

    public function update(UserUpdateRequest $request) {
        if($request->has('user.id')) {
            $this->userRepository->updateUserInfos($request->input('user.id'), $request->all());
        }
        return redirect()->back();
    }

    public function add(){
        return view('admin\add_users');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function addUser(UserAddRequest $request){
        $this->userRepository->store($request->all());
        return redirect()->back();
    }
}
