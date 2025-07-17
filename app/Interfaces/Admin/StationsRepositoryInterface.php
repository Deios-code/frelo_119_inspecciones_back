<?php

namespace App\Interfaces\Admin;

interface StationsRepositoryInterface
{
    public function getStationsList($idUser);
    public function getInfoStation($id, $idUser);

    public function updateStation($id, $idUser, array $data);
}
