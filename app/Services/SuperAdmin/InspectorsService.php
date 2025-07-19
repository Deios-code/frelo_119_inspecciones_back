<?php

namespace App\Services\SuperAdmin;

use App\Interfaces\SuperAdmin\InspectorsRepositoryInterface;

class InspectorsService
{
    protected $inspectorsRepository;

    public function __construct(InspectorsRepositoryInterface $repository)
    {
        $this->inspectorsRepository = $repository;
    }

    public function getInspectorsList()
    {
        $inspectors = $this->inspectorsRepository->getInspectorsList();

        if ($inspectors->isEmpty()) {
            return [];
        }

        $inspectors->transform(function ($inspector) {
            return [
                'code' => $inspector->id,
                'name' => $inspector->us_name.' '.$inspector->us_last_name,
                'phone' => $inspector->us_phone,
                'email' => $inspector->us_email,
                'station' => $inspector->inspector ? $inspector->inspector->station->st_name : null
            ];
        });

        return $inspectors;
    }

    public function getInfoInspector($id)
    {
        $inspector = $this->inspectorsRepository->getInfoInspector($id);

        if ($inspector->isEmpty()) {
            return [];
        }

        $inspector->transform(function ($user) {
            return [
                'code' => $user->id,
                'name' => $user->us_name.' '.$user->us_last_name,
                'phone' => $user->us_phone,
                'email' => $user->us_email,
                'type_document' => $user->us_type_document,
                'document' => $user->us_document,
                'birthday' => $user->us_birthday,
                'address' => $user->us_address,
                'station' => $user->inspector ? $user->inspector->station->st_name : null,
                'station_address' => $user->inspector ? $user->inspector->station->st_address : null,
                'station_phone' => $user->inspector ? $user->inspector->station->st_phone : null,
                'station_captain' => $user->inspector ? $user->inspector->station->user->us_name.' '.$user->inspector->station->user->us_last_name : null,
                'city' => $user->inspector ? $user->inspector->station->city->ci_name : null,
                'city_code' => $user->inspector ? $user->inspector->station->city->ci_dane : null,
                'department' => $user->inspector ? $user->inspector->station->city->department->de_name : null,
                'department_code' => $user->inspector ? $user->inspector->station->city->department->de_dane : null,
            ];
        });

        return $inspector;
    }
}
