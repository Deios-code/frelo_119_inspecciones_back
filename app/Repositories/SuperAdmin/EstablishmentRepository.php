<?php

namespace App\Repositories\SuperAdmin;
Use App\Interfaces\SuperAdmin\EstablishmentRepositoryInterface;
use App\Models\Establishment;

class EstablishmentRepository implements EstablishmentRepositoryInterface
{
    public function getEstablishmentsList()
    {
        return Establishment::with('city', 'station','inspections')->get();
    }

    public function getInfoEstablishment($id)
    {
        return Establishment::with('city', 'station','inspections.inspector.user')->find($id);
    }
}
