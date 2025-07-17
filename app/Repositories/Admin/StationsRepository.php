<?php

namespace App\Repositories\Admin;
Use App\Interfaces\Admin\StationsRepositoryInterface;
use App\Models\City;
use App\Models\Station;
use App\Models\User;

class StationsRepository implements StationsRepositoryInterface
{
    public function getStationsList($idUser)
    {
        return Station::with(['city', 'user'])->where('st_user_id', $idUser)->first();
    }

    public function getInfoStation($id, $idUser)
    {
        return Station::with(['city', 'user'])->where('id', $id)->where('st_user_id', $idUser)->first();
    }

    public function updateStation($id, $idUser, array $data)
    {
        $station = Station::where('id', $id)->where('st_user_id', $idUser)->first();
        if ($station) {
            $station->update($data);
            return $station;
        }
        return null;
    }
}
