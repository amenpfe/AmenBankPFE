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
        return view('user/edit-user');
    }

    public function editProfilPostUser(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('user/edit-user')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('user/edit-user')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function editProfilAdmin(){
        return view('admin/edit_profil');
    }

    public function editProfilPostAdmin(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('admin/edit_profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('admin/edit_profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilCED(){
        return view('ced/edit-profil');
    }

    public function editProfilCEDSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('ced/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('ced/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilCDD(){
        return view('cdd/edit-profil');
    }

    public function editProfilCDDSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('cdd/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('cdd/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilCDQ(){
        return view('cdq/edit-profil');
    }

    public function editProfilCDQSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('cdq/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('cdq/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilChd(){
        return view('chd/edit-profil');
    }

    public function editProfilChdSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('chd/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('chd/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilDS(){
        return view('ds/edit-profil');
    }

    public function editProfilDSSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('ds/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('ds/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilORG(){
        return view('organisation/edit-profil');
    }

    public function editProfilORGSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('organisation/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('organisation/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }

    public function getEditProfilProp(){
        return view('prop/edit-profil');
    }

    public function editProfilPropSubmit(EditProfilRequest $request){
        $user = Auth::user();
        $inputs = $request->all();
        $currentPassword = $inputs['current_password'];
        if(IlluminateHash::check($currentPassword, $user->password)){
            $inputs['role'] = $user->role;
            $this->userRepository->updateUserInfos($user->id, $inputs);
            return view('prop/edit-profil')->with('user', $user)->with("success", "Profil mis à jour!");
        }else {
            return view('prop/edit-profil')->with('user', $user)->with("error", "Mot de passe invalide!");
        }
    }


}
