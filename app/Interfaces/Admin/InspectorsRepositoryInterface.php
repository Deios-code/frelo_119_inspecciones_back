<?php

namespace App\Interfaces\Admin;

interface InspectorsRepositoryInterface
{
    public function getInspectorsList($idUser);
    public function getInfoInspector($idInspector, $idUser);
}
