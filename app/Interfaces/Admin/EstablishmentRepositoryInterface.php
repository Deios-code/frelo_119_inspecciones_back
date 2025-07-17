<?php

namespace App\Interfaces\Admin;

interface EstablishmentRepositoryInterface
{
    public function getEstablishmentsList($idUser);
    public function getInfoEstablishment($id, $idUser);
}
