<?php

namespace App\Interfaces\SuperAdmin;

interface EstablishmentRepositoryInterface
{
    public function getEstablishmentsList();
    public function getInfoEstablishment($id);
}
