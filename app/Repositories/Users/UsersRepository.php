<?php

namespace App\Repositories\Users;

use App\Models\User;

class UsersRepository
{
    public function getAllUsers()
    {
        return User::getUsers();
    }

    public function createUsers($name, $email, $password){
        return User::createUsers($name, $email, $password);
    }

    public function findUser($id){
        return User::findUser($id);
    }

}
