<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\InspectorsRepositoryInterface;
use App\Mail\RegisteredUserMail;
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
                'station' => $inspector->station->st_name
            ];
        });

        return $inspectors;
    }

    public function getInfoInspector($idInspector, $idUser)
    {
        $inspector = $this->inspectorsRepository->getInfoInspector($idInspector, $idUser);

        if ($inspector === null) {
            return [];
        }

        return [
            'user' => [
                'code' => $inspector->user->id,
                'name' => $inspector->user->us_name,
                'last_name' => $inspector->user->us_last_name,
                'phone' => $inspector->user->us_phone,
                'email' => $inspector->user->us_email,
                'type_document' => $inspector->user->us_type_document,
                'document' => $inspector->user->us_document,
                'birthday' => $inspector->user->us_birthday,
                'address' => $inspector->user->us_address,
                'code_inspector' => $inspector->id,
                'range' => $inspector->ins_range,
                'user_station' => $idUser,
                'station_code' => $inspector->station->id,
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

        return $inspector;
    }

    public function prepareCreateInspector($idUser)
    {
        $station = $this->inspectorsRepository->getStationByUserId($idUser);

        if (!$station) {
            return [];
        }
        // se devuelven los valores como array para ser utilizados en el frontend
        return [
            'station' => [
                [
                    'code' => $station->id,
                    'name' => $station->st_name,
                ],
            ],
            'city' => [
                [
                    'code' => $station->city->id,
                    'name' => $station->city->ci_name,
                ]
            ],
            'department' => [
                [
                    'code' => $station->city->department->id,
                    'name' => $station->city->department->de_name,
                ]
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
        try {
            DB::beginTransaction();

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
                'us_birthday' => date('Y-m-d', strtotime($request->birthday)),
                'us_address' => $request->address,
                'us_ci_id' => $request->city_code,
                'us_habeas_data' => true,
                'us_password' => Hash::make($password)
            ];

            $inspectorData = [
                'station_code' => $request->station_code,
                'range' => $request->range
            ];

            $userId = $this->inspectorsRepository->addInspector($userData, $inspectorData);

            Mail::to($request->email)->cc('echenawy99@gmail.com')->send(new RegisteredUserMail([
                'name' => $request->name.' '.$request->last_name,
                'user' => $request->email,
                'password' => $password
            ]));

            DB::commit();

            return [
                'error' => false,
                'msg' => 'Inspector creado exitosamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'error' => true,
                'msg' => $th->getMessage()
            ];
        }
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
                'us_birthday' => date('Y-m-d', strtotime($request->birthday)),
                'us_address' => $request->address,
                'us_habeas_data' => true
            ];

            if ($request->has('password')) {
                $userData['us_password'] = Hash::make($request->password);
            }

            $this->inspectorsRepository->updateInspector($idUser, $userData);

            $inspectorData = [
                'code_inspector' => $request->code_inspector,
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
