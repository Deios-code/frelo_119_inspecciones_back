<?php

namespace App\Interfaces\SuperAdmin;

interface InspectorsRepositoryInterface
{
    public function getInspectorsList();
    public function getInfoInspector($id);
}
