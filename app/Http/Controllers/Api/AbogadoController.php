<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAbogadoRequest;
use App\Http\Requests\UpdateAbogadoRequest;
use App\Http\Resources\AbogadoResource;
use App\Models\Abogado;
use App\Services\AbogadoService;
use Illuminate\Http\JsonResponse;

class AbogadoController extends Controller
{
    public function __construct(private readonly AbogadoService $service) {}

    /**
     * @group Abogados
     *
     * Lista todos los abogados ordenados alfabéticamente.
     *
     * @authenticated
     *
     * @response status=200 scenario="success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "cedula": "1234567890",
     *       "nombre": "Carlos",
     *       "apellido": "Ruiz",
     *       "nombre_completo": "Carlos Ruiz",
     *       "email": "carlos@example.com",
     *       "telefono": "3101234567",
     *       "especialidad": "Derecho Civil"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        return AbogadoResource::collection($this->service->list())->response();
    }

    /**
     * @group Abogados
     *
     * Crea un nuevo abogado.
     *
     * @authenticated
     *
     * @bodyParam cedula string required Cédula de identidad, única. Example: 9876543210
     * @bodyParam nombre string required Nombre del abogado. Example: Carlos
     * @bodyParam apellido string required Apellido del abogado. Example: Ruiz
     * @bodyParam email string Email del abogado. Example: carlos@example.com
     * @bodyParam telefono string Teléfono del abogado. Example: 3101234567
     * @bodyParam especialidad string Especialidad profesional. Example: Derecho Civil
     *
     * @response status=201 scenario="success" {
     *   "data": {
     *     "id": 2,
     *     "cedula": "9876543210",
     *     "nombre": "Carlos",
     *     "apellido": "Ruiz",
     *     "nombre_completo": "Carlos Ruiz",
     *     "email": "carlos@example.com",
     *     "telefono": "3101234567",
     *     "especialidad": "Derecho Civil"
     *   }
     * }
     */
    public function store(StoreAbogadoRequest $request): JsonResponse
    {
        return (new AbogadoResource($this->service->create($request->validated())))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * @group Abogados
     *
     * Devuelve la información de un abogado.
     *
     * @authenticated
     *
     * @urlParam abogado integer required ID del abogado. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "data": {
     *     "id": 1,
     *     "cedula": "1234567890",
     *     "nombre": "Carlos",
     *     "apellido": "Ruiz",
     *     "nombre_completo": "Carlos Ruiz",
     *     "email": "carlos@example.com",
     *     "telefono": "3101234567",
     *     "especialidad": "Derecho Civil"
     *   }
     * }
     */
    public function show(Abogado $abogado): JsonResponse
    {
        return (new AbogadoResource($abogado))->response();
    }

    /**
     * @group Abogados
     *
     * Actualiza los datos de un abogado.
     *
     * @authenticated
     *
     * @urlParam abogado integer required ID del abogado. Example: 1
     *
     * @bodyParam cedula string required Cédula de identidad, única. Example: 9876543210
     * @bodyParam nombre string required Nombre del abogado. Example: Carlos
     * @bodyParam apellido string required Apellido del abogado. Example: Ruiz
     * @bodyParam email string Email del abogado. Example: carlos@example.com
     * @bodyParam telefono string Teléfono del abogado. Example: 3101234567
     * @bodyParam especialidad string Especialidad profesional. Example: Derecho Civil
     */
    public function update(UpdateAbogadoRequest $request, Abogado $abogado): JsonResponse
    {
        return (new AbogadoResource($this->service->update($abogado, $request->validated())))->response();
    }

    /**
     * @group Abogados
     *
     * Elimina un abogado de forma lógica (soft delete).
     *
     * @authenticated
     *
     * @urlParam abogado integer required ID del abogado. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "message": "Abogado eliminado."
     * }
     */
    public function destroy(Abogado $abogado): JsonResponse
    {
        $this->service->delete($abogado);

        return response()->json([
            'message' => 'Abogado eliminado.',
        ]);
    }
}
