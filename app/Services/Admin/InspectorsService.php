<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\InspectorsRepositoryInterface;

class InspectorsService
{
    protected $inspectorsRepository;

    public function __construct(InspectorsRepositoryInterface $repository)
    {
        $this->inspectorsRepository = $repository;
    }

    public function getInspectorsList($idUser)
    {
        $inspectors = $this->inspectorsRepository->getInspectorsList($idUser);

        if ($inspectors->isEmpty()) {
            return [];
        }

        $inspectors->transform(function ($inspector) {
            return [
                'code' => $inspector->id,
                'name' => $inspector->user->us_name.' '.$inspector->user->us_last_name,
                'phone' => $inspector->user->us_phone,
                'email' => $inspector->user->us_email,
                'station' => $inspector->inspector ? $inspector->inspector->station->st_name : null
            ];
        });

        return $inspectors;
    }

    public function getInfoInspector($idInspector, $idUser)
    {
        $inspector = $this->inspectorsRepository->getInfoInspector($idInspector, $idUser);

        if ($inspector->isEmpty()) {
            return [];
        }

        $inspector->transform(function ($user) {
            return [
                'code' => $user->user->id,
                'name' => $user->user->us_name.' '.$user->user->us_last_name,
                'phone' => $user->user->us_phone,
                'email' => $user->user->us_email,
                'type_document' => $user->user->us_type_document,
                'document' => $user->user->us_document,
                'birthday' => $user->user->us_birthday,
                'address' => $user->user->us_address,
                'station' => $user->station ? $user->station->st_name : null,
                'station_address' => $user->station ? $user->station->st_address : null,
                'station_phone' => $user->station ? $user->station->st_phone : null,
                'station_captain' => $user->station ? $user->station->user->us_name.' '.$user->station->user->us_last_name : null,
                'city' => $user->station ? $user->station->city->ci_name : null,
                'city_code' => $user->station ? $user->station->city->ci_dane : null,
                'department' => $user->station ? $user->station->city->department->de_name : null,
                'department_code' => $user->station ? $user->station->city->department->de_dane : null,
            ];
        });

        return $inspector;
    }
}
