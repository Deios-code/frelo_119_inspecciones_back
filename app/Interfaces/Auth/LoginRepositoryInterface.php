<?php

namespace App\Interfaces\Auth;

interface LoginRepositoryInterface
{
    public function validateMail($email);
}
