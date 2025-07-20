<?php
//* come pingas .|.
namespace App\Services\Admin;

use App\Interfaces\Admin\EstablishmentRepositoryInterface;

class EstablishmentService
{
    protected $EstablishmentRepository;

    public function __construct(EstablishmentRepositoryInterface $repository)
    {
        $this->EstablishmentRepository = $repository;
    }

    public function getEstablishmentsList($idUser)
    {
        $establishments = $this->EstablishmentRepository->getEstablishmentsList($idUser);

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

    public function getInfoEstablishment($id, $idUser)
    {
        $establishment = $this->EstablishmentRepository->getInfoEstablishment($id, $idUser);

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
                    'date' => $inspection->created_at ? $inspection->created_at->format('Y-m-d') : null,
                    'status' => $inspection->in_state,
                    'consecutive' => $inspection->in_consecutive,
                    'inspector' => $inspection->inspector ? $inspection->inspector->user->us_name.' '.$inspection->inspector->user->us_last_name : null,
                ];
            }),
            'owner' => $establishment->user ? $establishment->user->us_name.' '.$establishment->user->us_last_name : null,
        ];
    }
}
