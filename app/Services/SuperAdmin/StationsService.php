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

    public function getAllStations()
    {
        $stations = $this->stationRepository->getAllStations();
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
                'userAsigned' => $station->user ? $station->user->us_name : null,
            ];
        });

        return $stations;
    }
}
