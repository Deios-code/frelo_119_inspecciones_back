<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Mail\RegisteredUserMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterService
{
    protected $registerRepository;

    public function __construct(RegisterRepositoryInterface $repository)
    {
        $this->registerRepository = $repository;
    }

    public function getDepartmentsList()
    {
        return $this->registerRepository->getDepartmentsList();
    }

    public function getCitiesList($departmentId)
    {
        return $this->registerRepository->getCities($departmentId);
    }

    public function register($data)
    {
        try {
            DB::beginTransaction();
            $password = Str::random(8);
            $data['password'] = $password;
            $response = $this->registerRepository->register($data);
            DB::commit();
            if (empty($response)) {
                DB::rollBack();
                return [
                    'error' => true,
                    'msg' => 'Error al registrar el usuario.'
                ];
            }

            Mail::to($response['user'])->cc('echenawy99@gmail.com')->send(new RegisteredUserMail([
                'name' => $response['name'],
                'user' => $response['user'],
                'password' => $password
            ]));

            return [
                'error' => false,
                'data' => $response
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'error' => true,
                'msg' => 'Error al registrar el usuario: ' . $th->getMessage()
            ];
        }
    }
}
