<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\EditProfilRequest;
use Illuminate\Support\Facades\Hash as IlluminateHash;

class UserController extends Controller
{
    protected $user;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function editProfilUser(){
        return view('edit-user');
    }

    public function editProfilPostUser(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('edit-user')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('edit-user')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

}
