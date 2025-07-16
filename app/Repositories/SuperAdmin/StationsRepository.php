<?php

namespace App\Repositories\SuperAdmin;
Use App\Interfaces\SuperAdmin\StationsRepositoryInterface;
use App\Models\Station;

class StationsRepository implements StationsRepositoryInterface
{
    public function getAllStations()
    {
        return Station::with(['city', 'user'])->get();
    }

    // Additional methods can be implemented here as needed
    // public function getStationById($id) { ... }
    // public function addStation(array $data) { ... }
    // public function updateStation($id, array $data) { ... }
    // public function deleteStation($id) { ... }
}
