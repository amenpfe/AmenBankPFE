<?php

namespace App\Repositories\User;


interface UserRepositoryInterface {
    public function getAll();
    public function getByEmail($email);
    public function getById($id);
    public function store($inputs);
    public function destroy($id);
    public function updateUserInfos($id, $inputs);
    public static function getUserByEmail($email);
    public function getUsersByRole($role);
    public function getUsersByRoles($roles);
}