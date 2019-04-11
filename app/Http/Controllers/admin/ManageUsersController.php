<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDeleteRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserAddRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\Enums\UserRole;
use App\Notifications\MailAddUserNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditProfilRequest;
use Illuminate\Support\Facades\Hash;

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

    public function addUser(UserAddRequest $request){
        $password = str_random(8);
        $inputs = $request->all();
        $inputs["password"] = $password;
        $user = $this->userRepository->store($inputs);
        //Mail::to($inputs["email"])->send(new SendMailable($inputs['name'], UserRole::getEnumDescriptionByValue((int)$inputs['role']), $password));
        $user->notify(new MailAddUserNotification($inputs['name'], UserRole::getEnumDescriptionByValue((int)$inputs['role']), $password));
        return redirect()->back();
    }

    public function editProfil(){
        return view('edit_profil');
    }

    public function editProfilPost(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(Hash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('edit_profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('edit_profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }
}
