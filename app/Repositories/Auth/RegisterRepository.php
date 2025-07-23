<?php

namespace App\Repositories\Auth;
Use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository implements RegisterRepositoryInterface
{
    public function getDepartmentsList()
    {
        return Department::orderBy('name', 'asc')->get(['id as code', 'de_name as name']);
    }

    public function getCities($departmentId)
    {
        return City::where('ci_de_id', $departmentId)->orderBy('name', 'asc')->get(['id as code', 'ci_name as name']);
    }

    public function register($data)
    {
        $user = new User();
        $user->us_name = $data['name'];
        $user->us_last_name = $data['last_name'];
        $user->us_email = $data['email'];
        $user->us_password = Hash::make($data['password']);
        $user->us_ci_id = $data['city'];
        $user->us_habeas_data = $data['habeas_data'];
        $user->save();

        return [
            'name' => $data['name'].' '.$data['last_name'],
            'user' => $data['email']
        ];
    }
}
