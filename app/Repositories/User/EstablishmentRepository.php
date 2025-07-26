<?php

namespace App\Repositories\User;
Use App\Interfaces\User\EstablishmentRepositoryInterface;
use App\Models\Establishment;
use App\Models\Station;
use App\Models\User;

class EstablishmentRepository implements EstablishmentRepositoryInterface
{
    public function getEstablishmentsList($userId)
    {
        return Establishment::with('city', 'station', 'inspections')->where('es_us_id', $userId)->get();
    }

    public function getInfoEstablishment($id, $userId)
    {
        return Establishment::with(['city', 'station','inspections.inspector.user'])->where('es_us_id', $userId)->where('id', $id)->first();
    }

    public function getCityAndStationByUser($userId)
    {
        $cityId = User::where('id', $userId)->value('us_ci_id');

        if (!$cityId) return null;

        return Station::with('city')
            ->where('st_city_id', $cityId)
            ->first();
    }

    public function getCityByUser($userId)
    {
        return User::with('city')->where('id', $userId)->first();
    }

    public function getStationIdByCityUser($cityId)
    {
        return Station::where('st_city_id', $cityId)->pluck('id')->first();
    }

    public function addEstablishment($data)
    {
        return Establishment::create($data);
    }

    public function updateEstablishment($id, $userId, $data)
    {
        $establishment = Establishment::where('id', $id)->where('es_us_id', $userId)->first();
        if ($establishment) {
            $establishment->update($data);
            return $establishment;
        }
        return null;
    }

    public function deleteEstablishment($id, $userId)
    {
        $establishment = Establishment::where('id', $id)->where('es_us_id', $userId)->first();
        if ($establishment) {
            return $establishment->delete();
        }
        return false;
    }

    public function updateInfoAdditionalUser($id, $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function verifyDataUnique($userId, $phone, $document)
    {
        return User::where('us_phone', $phone)->where('id', '!=', $userId)->exists() ||
               User::where('us_document', $document)->where('id', '!=', $userId)->exists();
    }

    public function getInfoAdditionalUser($userId)
    {
        return User::find($userId);
    }
}
