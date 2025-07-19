<?php

namespace App\Repositories\Admin;
Use App\Interfaces\Admin\InspectorsRepositoryInterface;
use App\Models\Inspector;

class InspectorsRepository implements InspectorsRepositoryInterface
{
    public function getInspectorsList($idUser)
    {
        return Inspector::with(['user', 'station'])->whereHas('station', function ($query) use ($idUser) {
            $query->where('st_user_id', $idUser);
        })->get();
    }

    public function getInfoInspector($idInspector, $idUser)
    {
        return Inspector::with(['station.city.department', 'user'])->where('id', $idInspector)->whereHas('station', function ($query) use ($idUser) {
            $query->where('st_user_id', $idUser);
        })->get();
    }
}
