<?php

namespace App\Interfaces\Admin;

interface InspectorsRepositoryInterface
{
    public function getInspectorsList($idUser);
    public function getInfoInspector($idInspector, $idUser);
    public function getStationByUserId($idUser);
    public function verifyInspector($idInspector);

    public function addInspector($data, $inspectorData);
    public function updateInspector($idUser, $data);
    public function updateAssignUserStation($inspectorData);
}
