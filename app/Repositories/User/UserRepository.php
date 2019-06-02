<?php

namespace App\Repositories\User;
use Illuminate\Support\Facades\DB;
use App\User;

class UserRepository implements UserRepositoryInterface {

    protected $user;
    
    function __construct(User $user)
    {
        $this->user = $user;
    }

    function save(User $user, $inputs) {
        $user->name = $inputs['name'];
        $user->adresse = $inputs['adresse'];
        $user->phone = $inputs['phone'];
        $user->email = $inputs['email'];
        if(array_key_exists('password', $inputs) && $inputs['password']!=''){
            $user->password = bcrypt($inputs['password']);
        }
        $user->role = $inputs['role'];
        $user->save();
        return $user;
    }

    public function getAll()
    {
        return $this->user->all();
    }

    public function getByEmail($email)
    {
        return $this->user->findByEmail($email);
    }

    public function getById($id)
    {
        return $this->user->findOrFail($id);
    }

    public function store($inputs)
    {
        $user = new $this->user;
        
        return $this->save($user, $inputs);
    }

    public function destroy($id) {
        $this->getById($id)->delete();
    }

    public function updateUserInfos($id, $inputs)
    {
        $u = $this->getById($id);
        return $this->save($u, $inputs);
    }

    public static function getUserByEmail($email)
    {
        return $this->getByEmail($email);
    }

    public function getUsersByRole($role)
    {
        return $this->user->findUsersByRole($role);
    }

    public function getUsersByRoles($roles)
    {   
        $users = [];
        foreach ($roles as $role) {
            $users = array_merge($users, getUsersByRole($role));
        }

        return $users;
    }
}

