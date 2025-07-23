<?php

namespace App\Interfaces\Auth;

interface RegisterRepositoryInterface
{
    public function getDepartmentsList();
    public function getCities($departmentId);
    public function register($data);
}
