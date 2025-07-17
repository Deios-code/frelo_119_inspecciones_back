<?php

namespace App\Services\SuperAdmin;

use App\Interfaces\SuperAdmin\EstablishmentRepositoryInterface;

class EstablishmentService
{
    protected $EstablishmentRepository;

    public function __construct(EstablishmentRepositoryInterface $repository)
    {
        $this->EstablishmentRepository = $repository;
    }

    public function getEstablishmentsList()
    {
        $establishments = $this->EstablishmentRepository->getEstablishmentsList();

        if ($establishments->isEmpty()) {
            return [];
        }


        $establishments->transform(function ($establishment) {
            return [
                'code' => $establishment->id,
                'name' => $establishment->es_name_establishment,
                'phone' => $establishment->es_phone,
                'address' => $establishment->es_address,
                'city' => $establishment->city ? $establishment->city->ci_name : null,
                'station' => $establishment->station ? $establishment->station->st_name : null,
                'inspections' => $establishment->inspections->count(),
                'owner' => $establishment->user ? $establishment->user->us_name.' '.$establishment->user->us_last_name : null,
            ];
        });

        return $establishments;
    }

    public function getInfoEstablishment($id)
    {
        $establishment = $this->EstablishmentRepository->getInfoEstablishment($id);

        if (!$establishment) {
            return null;
        }

        return [
            'code' => $establishment->id,
            'name' => $establishment->es_name_establishment,
            'phone' => $establishment->es_phone,
            'address' => $establishment->es_address,
            'nit' => $establishment->es_nit,
            'commune' => $establishment->es_commune,
            'neighborhood' => $establishment->es_neighborhood,
            'email_establishment' => $establishment->es_email_establishment,
            'city' => $establishment->city ? $establishment->city->ci_name : null,
            'station' => $establishment->station ? $establishment->station->st_name : null,
            'inspections_count' => $establishment->inspections->count(),
            'inspections' => $establishment->inspections->map(function ($inspection) {
                return [
                    'code' => $inspection->id,
                    'date' => $inspection->created_at->format('Y-m-d'),
                    'status' => $inspection->in_state,
                    'consecutive' => $inspection->in_consecutive,
                    // 'inspectors' => $inspection->inspectors->us_name.' '.$inspection->inspectors->us_last_name
                ];
            }),
            'owner' => $establishment->user ? $establishment->user->us_name.' '.$establishment->user->us_last_name : null,
        ];
    }
}
