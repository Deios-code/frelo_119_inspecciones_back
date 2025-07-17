<?php

namespace App\Interfaces\SuperAdmin;

interface StationsRepositoryInterface
{
    public function getStationsList();
    public function getInfoStation($id);
    public function getCitiesList();
    public function getUsersList();

    public function addStation(array $data);
    public function updateStation($id, array $data);
    public function deleteStation($id);
}
