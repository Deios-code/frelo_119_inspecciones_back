<?php

namespace App\Services\User;

use App\Interfaces\User\EstablishmentRepositoryInterface;
use App\Services\ManageToken\ManageTokenService;
use Illuminate\Support\Facades\Log;

class EstablishmentService
{
    protected $EstablishmentRepository;
    private $manageTokenService;

    public function __construct(EstablishmentRepositoryInterface $repository, ManageTokenService $manageTokenService)
    {
        $this->EstablishmentRepository = $repository;
        $this->manageTokenService = $manageTokenService;
    }

    public function getEstablishmentsList($request)
    {
        $userId = $this->manageTokenService->getIdbyToken($request);
        $establishments = $this->EstablishmentRepository->getEstablishmentsList($userId);

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

    public function getInfoEstablishment($request, $establishmentId)
    {
        $userId = $this->manageTokenService->getIdbyToken($request);
        $establishment = $this->EstablishmentRepository->getInfoEstablishment($establishmentId, $userId);

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

    public function addEstablishment($request)
    {
        try {
            $userId = $this->manageTokenService->getIdbyToken($request);
            $location = $this->EstablishmentRepository->getCityAndStationByUser($userId);

            if (!$location || !$location->id || !$location->city) {
                return [
                    'error' => true,
                    'message' => 'City not found for the user.'
                ];
            }

            $establishment = [
                'es_name_establishment' => $request->input('name_establishment'),
                'es_phone' => $request->input('phone'),
                'es_address' => $request->input('address'),
                'es_nit' => $request->input('nit'),
                'es_email_establishment' => $request->input('email'),
                'es_commune' => $request->input('commune'),
                'es_neighborhood' => $request->input('neighborhood'),
                'es_ci_id' => $location->city->id,
                'es_exoneration' => $request->input('exoneration') ? "SI" : "NO",
                'es_us_id' => $userId,
                'es_station_id' => $location->id
            ];

            $establishment = $this->EstablishmentRepository->addEstablishment($establishment);
            return [
                'error' => false,
                'data' => $establishment->id
            ];
        } catch (\Throwable $th) {
            Log::error('Error en EstablishmentService@addEstablishment: ' . $th->getMessage());
            return [
                'error' => true,
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ];
        }
    }

    public function updateEstablishment($request)
    {
        try {
            $userId = $this->manageTokenService->getIdbyToken($request);
            $establishmentData = [
                'es_name_establishment' => $request->input('name_establishment'),
                'es_phone' => $request->input('phone'),
                'es_address' => $request->input('address'),
                'es_nit' => $request->input('nit'),
                'es_email_establishment' => $request->input('email'),
                'es_commune' => $request->input('commune'),
                'es_neighborhood' => $request->input('neighborhood'),
                'es_exoneration' => $request->input('exoneration') ? "SI" : "NO",
            ];

            $establishment = $this->EstablishmentRepository->updateEstablishment($request->input('code'), $userId, $establishmentData);

            if (!$establishment) {
                return [
                    'error' => true,
                    'message' => 'Establishment not found or update failed.'
                ];
            }

            return [
                'error' => false,
                'data' => $establishment->id
            ];

        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ];
        }
    }

    public function updateInfoAdditionalUser($request)
    {
        try {
            $userId = $this->manageTokenService->getIdbyToken($request);

            $user = [
                'us_type_document' => $request->input('type_document'),
                'us_document' => $request->input('document'),
                'us_birthday' => date('Y-m-d', strtotime($request->input('birthday'))),
                'us_address' => $request->input('address'),
                'us_phone' => $request->input('phone'),
            ];

            $verifyPhone = $this->EstablishmentRepository->verifyDataUnique($userId, $user['us_phone'], $user['us_document']);
            if ($verifyPhone) {
                return [
                    'error' => true,
                    'message' => 'The phone number or document already exists for another user. Please verify the data and try again.'
                ];
            }
            $user = $this->EstablishmentRepository->updateInfoAdditionalUser($userId, $user);

            if (!$user || !isset($user)) {
                return [
                    'error' => true,
                    'message' => 'User not found'
                ];
            }

            return [
                'error' => false,
                'data' => $user->id
            ];
        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ];
        }
    }

    public function getInfoAdditionalUser($request)
    {
        try {
            $userId = $this->manageTokenService->getIdbyToken($request);
            $user = $this->EstablishmentRepository->getInfoAdditionalUser($userId);

            return [
                'error' => false,
                'data' => [
                    'type_document' => $user->us_type_document,
                    'document' => $user->us_document,
                    'birthday' => $user->us_birthday ?? null,
                    'address' => $user->us_address,
                    'phone' => $user->us_phone,
                    'has_aditional_info' => $user->us_document ? true : false
                ]
            ];

        } catch (\Throwable $th) {
            return [
                'error' => true,
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ];
        }
    }
}
