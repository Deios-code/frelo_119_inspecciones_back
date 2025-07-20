<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\InspectorsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InspectorsService
{
    protected $inspectorsRepository;

    public function __construct(InspectorsRepositoryInterface $repository)
    {
        $this->inspectorsRepository = $repository;
    }

    public function getInspectorsList($idUser)
    {
        $inspectors = $this->inspectorsRepository->getInspectorsList($idUser);

        if ($inspectors->isEmpty()) {
            return [];
        }

        $inspectors->transform(function ($inspector) {
            return [
                'code' => $inspector->user->id,
                'name' => $inspector->user->us_name.' '.$inspector->user->us_last_name,
                'phone' => $inspector->user->us_phone,
                'email' => $inspector->user->us_email,
                'station' => $inspector->inspector ? $inspector->inspector->station->st_name : null
            ];
        });

        return $inspectors;
    }

    public function getInfoInspector($idInspector, $idUser)
    {
        $inspector = $this->inspectorsRepository->getInfoInspector($idInspector, $idUser);

        if ($inspector->isEmpty()) {
            return [];
        }

        $inspector->transform(function ($user) {
            return [
                'user' => [
                    'code' => $user->user->id,
                    'name' => $user->user->us_name.' '.$user->user->us_last_name,
                    'phone' => $user->user->us_phone,
                    'email' => $user->user->us_email,
                    'type_document' => $user->user->us_type_document,
                    'document' => $user->user->us_document,
                    'birthday' => $user->user->us_birthday,
                    'address' => $user->user->us_address,
                    'code_inspector' => $user->id
                ],
                'station' => [
                    'code' => $user->station->id,
                    'name' => $user->station->st_name,
                    'address' => $user->station ? $user->station->st_address : null,
                    'phone' => $user->station ? $user->station->st_phone : null,
                    'captain' => $user->station ? $user->station->user->us_name.' '.$user->station->user->us_last_name : null,
                ],
                'city' => [
                    'code' => $user->station->city->id,
                    'name' => $user->station->city->ci_name,
                ],
                'department' => [
                    'code' => $user->station->city->department->code,
                    'name' => $user->station->city->department->de_name,
                ],
                'ranges' =>[
                    [
                        'code' => 'CAPITAN',
                        'name' => 'Capitán'
                    ],
                    [
                        'code' => 'TENIENTE',
                        'name' => 'Teniente'
                    ],
                    [
                        'code' => 'SUBTENIENTE',
                        'name' => 'Subteniente'
                    ],
                    [
                        'code' => 'SARGENTO',
                        'name' => 'Sargento'
                    ],
                    [
                        'code' => 'CABO',
                        'name' => 'Cabo'
                    ],
                    [
                        'code' => 'BOMBERO',
                        'name' => 'Bombero'
                    ],
                    [
                        'code' => 'INSPECTOR',
                        'name' => 'Inspector'
                    ]
                ],
                'type_documents' => [
                    [
                        'code' => 'CEDULA',
                        'name' => 'Cédula de Ciudadanía'
                    ],
                    [
                        'code' => 'CEDULA_EXTRANJERIA',
                        'name' => 'Cédula de Extranjería'
                    ],
                    [
                        'code' => 'PASAPORTE',
                        'name' => 'Pasaporte'
                    ],
                ]
            ];
        });

        return $inspector;
    }

    public function prepareCreateInspector($idUser)
    {
        $station = $this->inspectorsRepository->getStationByUserId($idUser);

        if (!$station) {
            return [];
        }

        return [
            'station' => [
                'code' => $station->id,
                'name' => $station->st_name,
            ],
            'city' => [
                'code' => $station->city->id,
                'name' => $station->city->ci_name,
            ],
            'department' => [
                'code' => $station->city->department->id,
                'name' => $station->city->department->de_name,
            ],
            'ranges' =>[
                [
                    'code' => 'CAPITAN',
                    'name' => 'Capitán'
                ],
                [
                    'code' => 'TENIENTE',
                    'name' => 'Teniente'
                ],
                [
                    'code' => 'SUBTENIENTE',
                    'name' => 'Subteniente'
                ],
                [
                    'code' => 'SARGENTO',
                    'name' => 'Sargento'
                ],
                [
                    'code' => 'CABO',
                    'name' => 'Cabo'
                ],
                [
                    'code' => 'BOMBERO',
                    'name' => 'Bombero'
                ],
                [
                    'code' => 'INSPECTOR',
                    'name' => 'Inspector'
                ]
            ],
            'type_documents' => [
                [
                    'code' => 'CEDULA',
                    'name' => 'Cédula de Ciudadanía'
                ],
                [
                    'code' => 'CEDULA_EXTRANJERIA',
                    'name' => 'Cédula de Extranjería'
                ],
                [
                    'code' => 'PASAPORTE',
                    'name' => 'Pasaporte'
                ],
            ]
        ];
    }

    public function addInspector($request)
    {
        $password = Str::random(8);
        $stationUser = $this->inspectorsRepository->getStationByUserId($request->user_station);
        if(!$stationUser || $stationUser->id !== $request->station_code) {
            return [
                'error' => true,
                'msg' => 'La estación no pertenece al usuario especificado.'
            ];
        }

        $userData = [
            'us_name' => $request->name,
            'us_role' => 'INSPECTOR',
            'us_last_name' => $request->last_name,
            'us_phone' => $request->phone,
            'us_email' => $request->email,
            'us_type_document' => $request->type_document,
            'us_document' => $request->document,
            'us_birthday' => $request->birthday,
            'us_address' => $request->address,
            'us_ci_id' => $request->city_code,
            'us_habeas_data' => true,
            'us_password' => Hash::make($password)
        ];

        $inspectorData = [
            'station_code' => $request->station_code,
            'range' => $request->range
        ];

        $userId = $this->inspectorsRepository->addInspector($userData, $inspectorData)->id;

        // Mail::to($request->email)->send(new InspectorCreated($userData, $password));

        return [
            'error' => false,
            'msg' => 'Inspector creado exitosamente.'
        ];
    }

    public function updateInspector($request, $idUser)
    {
        try {
            DB::beginTransaction();

            $stationUser = $this->inspectorsRepository->getStationByUserId($request->user_station);
            if(!$stationUser || $stationUser->id !== $request->station_code) {
                return [
                    'error' => true,
                    'msg' => 'La estación no pertenece al usuario especificado.'
                ];
            }

            $idInspector = $this->inspectorsRepository->verifyInspector($request->code_inspector);
            if (!$idInspector) {
                return [
                    'error' => true,
                    'msg' => 'relation Inspector not found'
                ];
            }

            $userData = [
                'us_name' => $request->name,
                'us_role' => 'INSPECTOR',
                'us_last_name' => $request->last_name,
                'us_phone' => $request->phone,
                'us_email' => $request->email,
                'us_type_document' => $request->type_document,
                'us_document' => $request->document,
                'us_birthday' => $request->birthday,
                'us_address' => $request->address,
                'us_ci_id' => $request->city_code,
                'us_habeas_data' => true
            ];

            if ($request->has('password')) {
                $userData['us_password'] = Hash::make($request->password);
            }

            $this->inspectorsRepository->updateInspector($idUser, $userData);

            $inspectorData = [
                'code_inspector' => $request->code_inspector,
                'station_code' => $request->station_code,
                'range' => $request->range,
                'code_user' => $request->code
            ];

            $this->inspectorsRepository->updateAssignUserStation($inspectorData);

            DB::commit();

            return [
                'error' => false,
                'msg' => 'Inspector actualizado exitosamente.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'msg' => $e->getMessage()
            ];
        }
    }
}
