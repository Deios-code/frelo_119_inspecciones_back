<?php

namespace App\Repositories\Auth;
Use App\Interfaces\Auth\LoginRepositoryInterface;
use App\Models\User;

class LoginRepository implements LoginRepositoryInterface
{
    public function validateMail($email)
    {
        return User::where('us_email', $email)->first();
    }
}
