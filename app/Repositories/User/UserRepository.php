<?php

namespace App\Repositories\User;

use App\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface {
    
    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    function save(User $user, $inputs) {
        $user->name = $inputs['name'];
        $user->email = $inputs['email'];
        if($inputs['password'] && $inputs['password']!=''){
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
}

