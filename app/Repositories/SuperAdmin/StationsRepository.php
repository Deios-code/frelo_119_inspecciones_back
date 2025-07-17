<?php

namespace App\Repositories\SuperAdmin;
Use App\Interfaces\SuperAdmin\StationsRepositoryInterface;
use App\Models\City;
use App\Models\Station;
use App\Models\User;

class StationsRepository implements StationsRepositoryInterface
{
    public function getStationsList()
    {
        return Station::with(['city', 'user'])->get();
    }

    public function getInfoStation($id)
    {
        return Station::with(['city', 'user'])->find($id);
    }

    public function getCitiesList()
    {
        return City::get(['id as code', 'ci_name as name']);
    }

    public function getUsersList()
    {
        return User::where('us_role', 'ADMIN')->get(['id as code', 'us_name as name', 'us_last_name as lastName']);
    }

    public function addStation(array $data)
    {
        return Station::create($data);
    }

    public function updateStation($id, array $data)
    {
        $station = Station::find($id);
        if ($station) {
            $station->update($data);
            return $station;
        }
        return null;
    }

    public function deleteStation($id)
    {
        $station = Station::find($id);
        if ($station) {
            $station->delete();
            return true;
        }
        return false;
    }
}
