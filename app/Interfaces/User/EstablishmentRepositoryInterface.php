<?php

namespace App\Interfaces\User;

interface EstablishmentRepositoryInterface
{
    public function getEstablishmentsList($userId);
    public function getInfoEstablishment($id, $userId);
    public function getStationIdByCityUser($userId);
    public function getCityAndStationByUser($userId);

    public function getCityByUser($userId);
    public function addEstablishment($data);
    public function updateEstablishment($id, $userId, $data);
    public function deleteEstablishment($id, $userId);
    public function verifyDataUnique($userId, $phone, $document);

    public function updateInfoAdditionalUser($id, $data);
    public function getInfoAdditionalUser($userId);
}
