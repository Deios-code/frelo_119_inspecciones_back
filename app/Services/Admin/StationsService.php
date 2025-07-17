<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\StationsRepositoryInterface;

class StationsService
{
    protected $stationRepository;

    public function __construct(StationsRepositoryInterface $repository)
    {
        $this->stationRepository = $repository;
    }

    public function getStationsList($idUser)
    {
        $station = $this->stationRepository->getStationsList($idUser);
        if (!$station) {
            return [];
        }

        return [
            'code' => $station->id,
            'name' => $station->st_name,
            'phone' => $station->st_phone,
            'address' => $station->st_address,
            'city' => $station->city ? $station->city->ci_name : null,
            'userAsigned' => $station->user ? $station->user->us_name.' '.$station->user->us_last_name : null,
        ];
    }

    public function getInfoStation($id, $idUser)
    {
        $station = $this->stationRepository->getInfoStation($id, $idUser);
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

        return [
            'station' => $infoStation,
            'users' => [
                'code' => $station->user ? $station->user->id : null,
                'name' => $station->user ? $station->user->us_name.' '.$station->user->us_last_name : null,
            ],
            'cities' => [
                'code' => $station->city ? $station->city->id : null,
                'name' => $station->city ? $station->city->ci_name : null,
            ]
        ];
    }

    public function updateStation($id, array $data)
    {
        $stationUpdate = [
            'st_name' => $data['name'],
            'st_nit' => $data['nit'],
            'st_address' => $data['address'],
            'st_phone' => $data['phone']
        ];

        if (isset($data['longitude'])) {
            $stationUpdate['st_longitude'] = $data['longitude'];
        }
        if (isset($data['latitude'])) {
            $stationUpdate['st_latitude'] = $data['latitude'];
        }

        $this->stationRepository->updateStation($id, $data['codeUserAsigned'], $stationUpdate);

        return $data;
    }
}
