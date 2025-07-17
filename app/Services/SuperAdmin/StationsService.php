<?php

namespace App\Services\SuperAdmin;

use App\Interfaces\SuperAdmin\StationsRepositoryInterface;

class StationsService
{
    protected $stationRepository;

    public function __construct(StationsRepositoryInterface $repository)
    {
        $this->stationRepository = $repository;
    }

    public function getStationsList()
    {
        $stations = $this->stationRepository->getStationsList();
        if ($stations->isEmpty()) {
            return [];
        }

        // Transform the stations data if necessary
        $stations->transform(function ($station) {
            return [
                'code' => $station->id,
                'name' => $station->st_name,
                'phone' => $station->st_phone,
                'address' => $station->st_address,
                'city' => $station->city ? $station->city->ci_name : null,
                'userAsigned' => $station->user ? $station->user->us_name.' '.$station->user->us_last_name : null,
            ];
        });

        return $stations;
    }

    public function getInfoStation($id)
    {
        $station = $this->stationRepository->getInfoStation($id);
        if (!$station) {
            return [];
        }

        $infoStation = [
            'code' => $station->id,
            'name' => $station->st_name,
            'phone' => $station->st_phone,
            'address' => $station->st_address,
            'nit' => $station->st_nit,
            'longitude' => $station->st_longitude,
            'latitude' => $station->st_latitude,
            'codeCity' => $station->city ? $station->city->id : null,
            'codeUserAsigned' => $station->user ? $station->user->id : null,
        ];

        $users = $this->stationRepository->getUsersList();
        if ($users->isEmpty()) {
            $users = [];
        } else {
            $users->transform(function ($user) {
                return [
                    'code' => $user->code,
                    'name' => $user->name.' '.$user->lastName,
                ];
            });
        }

        return [
            'station' => $infoStation,
            'users' => $users,
            'cities' => $this->stationRepository->getCitiesList()
        ];
    }
    public function prepareCreateStation()
    {
        $users = $this->stationRepository->getUsersList();
        if ($users->isEmpty()) {
            $users = [];
        } else {
            $users->transform(function ($user) {
                return [
                    'code' => $user->code,
                    'name' => $user->name.' '.$user->lastName,
                ];
            });
        }

        return [
            'users' => $users,
            'cities' => $this->stationRepository->getCitiesList()
        ];
    }

    public function addStation(array $data)
    {
        $stationCreate = [
            'st_name' => $data['name'],
            'st_nit' => $data['nit'],
            'st_address' => $data['address'],
            'st_phone' => $data['phone'],
            'st_longitude' => $data['longitude'] ?? null,
            'st_latitude' => $data['latitude'] ?? null,
            'st_city_id' => $data['codeCity'],
            'st_user_id' => $data['codeUserAsigned'] ?? null
        ];

        $this->stationRepository->addStation($stationCreate);

        return $data;
    }

    public function updateStation($id, array $data)
    {
        $stationUpdate = [
            'st_name' => $data['name'],
            'st_nit' => $data['nit'],
            'st_address' => $data['address'],
            'st_phone' => $data['phone'],
            'st_city_id' => $data['codeCity'],
        ];

        if (isset($data['longitude'])) {
            $stationUpdate['st_longitude'] = $data['longitude'];
        }
        if (isset($data['latitude'])) {
            $stationUpdate['st_latitude'] = $data['latitude'];
        }
        if (isset($data['codeUserAsigned'])) {
            $stationUpdate['st_user_id'] = $data['codeUserAsigned'];
        }

        $this->stationRepository->updateStation($id, $stationUpdate);

        return $data;
    }

    public function deleteStation($id)
    {
        // Validate and process the data as needed
        return $this->stationRepository->deleteStation($id);
    }
}
