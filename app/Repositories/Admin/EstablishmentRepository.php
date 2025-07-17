<?php

namespace App\Repositories\Admin;
Use App\Interfaces\Admin\EstablishmentRepositoryInterface;
use App\Models\Establishment;

class EstablishmentRepository implements EstablishmentRepositoryInterface
{
    // private function getStationUser($idUser)
    // {
    //     return Establishment::where('es_us_id', $idUser)->pluck('es_station_id')->first();
    // }

    public function getEstablishmentsList($idUser)
    {
        return Establishment::with('city', 'station', 'inspections')->whereHas('station', function ($query) use ($idUser) {
            $query->where('st_user_id', $idUser);
        })->get();
    }

    public function getInfoEstablishment($id, $idUser)
    {
        return Establishment::with(['city', 'station','inspections.inspector.user'])->whereHas('station', function ($query) use ($idUser) {
            $query->where('st_user_id', $idUser);
        })->where('id', $id)->first();
    }
}
