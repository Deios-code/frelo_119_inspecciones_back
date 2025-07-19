<?php

namespace App\Repositories\SuperAdmin;
Use App\Interfaces\SuperAdmin\InspectorsRepositoryInterface;
use App\Models\User;

class InspectorsRepository implements InspectorsRepositoryInterface
{
    public function getInspectorsList()
    {
        return User::with('inspector.station')->where('us_role', 'INSPECTOR')->get();
    }
    public function getInfoInspector($id)
    {
        return User::with(['inspector.station.city.department', 'inspector.station.user'])->where('id', $id)->get();
    }
}
