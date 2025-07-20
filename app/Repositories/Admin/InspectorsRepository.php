<?php

namespace App\Repositories\Admin;
Use App\Interfaces\Admin\InspectorsRepositoryInterface;
use App\Models\Inspector;
use App\Models\Station;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        return Inspector::with(['station.city.department','user'])->whereHas('user', function ($query) use ($idInspector) {
                $query->where('id', $idInspector);
            })->whereHas('station', function ($query) use ($idUser) {
                $query->where('st_user_id', $idUser);
            })->get();
    }

    public function getStationByUserId($idUser)
    {
        return Station::with(['city.department'])->where('st_user_id', $idUser)->first();
    }

    public function addInspector($data, $inspectorData)
    {
        try {
            $userId = User::create($data);
            $this->assignUserStation($userId->id, $inspectorData);
            DB::commit();
            return $userId;
        } catch (\Exception $e) {
            DB::rollBack();
            return [];
        }

    }

    private function assignUserStation($idInspector, $inspectorData)
    {
        $inspector = new Inspector();
        $inspector->ins_id_user = $idInspector;
        $inspector->ins_id_station = $inspectorData['station_code'];
        $inspector->ins_range = $inspectorData['range'];
        return $inspector->save();
    }

    public function verifyInspector($idInspector)
    {
        return Inspector::find($idInspector);
    }

    public function updateInspector($idUser, $data)
    {
        $userId = User::find($idUser);
        return $userId->update($data);
    }

    public function updateAssignUserStation($inspectorData)
    {
        $inspector = Inspector::find($inspectorData['code_inspector']);
        if ($inspector) {
            $inspector->ins_range = $inspectorData['range'];
            return $inspector->save();
        }
        return false;
    }
}
