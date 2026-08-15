<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCasoRequest;
use App\Http\Requests\UpdateCasoRequest;
use App\Http\Resources\CasoResource;
use App\Models\Caso;
use App\Services\CasoService;
use Illuminate\Http\JsonResponse;

class CasoController extends Controller
{
    public function __construct(private readonly CasoService $service) {}

    /**
     * @group Casos
     *
     * Lista todos los casos, incluyendo cliente y abogados.
     *
     * @authenticated
     *
     * @response status=200 scenario="success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "numero_expediente": "EXP-0001-2024",
     *       "estado": {
     *         "value": "en_tramite",
     *         "label": "En trámite"
     *       },
     *       "fecha_inicio": "2024-01-15",
     *       "fecha_finalizacion": null,
     *       "descripcion": "Demanda laboral",
     *       "cliente": {
     *         "id": 1,
     *         "cedula": "1234567890",
     *         "nombre": "Ana",
     *         "apellido": "Gómez",
     *         "nombre_completo": "Ana Gómez",
     *         "email": "ana@example.com",
     *         "telefono": "3001234567",
     *         "direccion": "Calle 1 #2-3"
     *       },
     *       "abogados": []
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        return CasoResource::collection($this->service->list())->response();
    }

    /**
     * @group Casos
     *
     * Crea un nuevo caso y asigna los abogados indicados.
     *
     * @authenticated
     *
     * @bodyParam numero_expediente string required Número de expediente, único. Example: EXP-0002-2024
     * @bodyParam cliente_id integer required ID del cliente. Example: 1
     * @bodyParam fecha_inicio date required Fecha de inicio del caso. Example: 2024-03-01
     * @bodyParam fecha_finalizacion date Fecha de finalización (si aplica). Example: 2024-09-01
     * @bodyParam estado string required Estado del caso. Enum: en_tramite, archivado, sentenciado, desistido, suspendido. Example: en_tramite
     * @bodyParam descripcion string Descripción del caso. Example: Contrato incumplido
     * @bodyParam abogados array<int> Lista de IDs de abogados asignados. Example: [1, 2]
     *
     * @response status=201 scenario="success" {
     *   "data": {
     *     "id": 2,
     *     "numero_expediente": "EXP-0002-2024",
     *     "estado": {
     *       "value": "en_tramite",
     *       "label": "En trámite"
     *     },
     *     "fecha_inicio": "2024-03-01",
     *     "fecha_finalizacion": null,
     *     "descripcion": "Contrato incumplido",
     *     "cliente": {
     *       "id": 1,
     *       "cedula": "1234567890",
     *       "nombre": "Ana",
     *       "apellido": "Gómez",
     *       "nombre_completo": "Ana Gómez",
     *       "email": "ana@example.com",
     *       "telefono": "3001234567",
     *       "direccion": "Calle 1 #2-3"
     *     },
     *     "abogados": [
     *       {
     *         "id": 1,
     *         "cedula": "1234567890",
     *         "nombre": "Carlos",
     *         "apellido": "Ruiz",
     *         "nombre_completo": "Carlos Ruiz",
     *         "email": "carlos@example.com",
     *         "telefono": "3101234567",
     *         "especialidad": "Derecho Civil"
     *       }
     *     ]
     *   }
     * }
     */
    public function store(StoreCasoRequest $request): JsonResponse
    {
        return (new CasoResource($this->service->create($request->validated())))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * @group Casos
     *
     * Devuelve toda la información de un caso, incluyendo cliente y abogados.
     *
     * @authenticated
     *
     * @urlParam caso integer required ID del caso. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "data": {
     *     "id": 1,
     *     "numero_expediente": "EXP-0001-2024",
     *     "estado": {
     *       "value": "en_tramite",
     *       "label": "En trámite"
     *     },
     *     "fecha_inicio": "2024-01-15",
     *     "fecha_finalizacion": null,
     *     "descripcion": "Demanda laboral",
     *     "cliente": {
     *       "id": 1,
     *       "cedula": "1234567890",
     *       "nombre": "Ana",
     *       "apellido": "Gómez",
     *       "nombre_completo": "Ana Gómez",
     *       "email": "ana@example.com",
     *       "telefono": "3001234567",
     *       "direccion": "Calle 1 #2-3"
     *     },
     *     "abogados": [
     *       {
     *         "id": 1,
     *         "cedula": "1234567890",
     *         "nombre": "Carlos",
     *         "apellido": "Ruiz",
     *         "nombre_completo": "Carlos Ruiz",
     *         "email": "carlos@example.com",
     *         "telefono": "3101234567",
     *         "especialidad": "Derecho Civil"
     *       }
     *     ],
     *     "created_at": "2024-01-15T10:00:00+00:00",
     *     "updated_at": "2024-01-15T10:00:00+00:00"
     *   }
     * }
     */
    public function show(Caso $caso): JsonResponse
    {
        $caso->load(['cliente', 'abogados']);

        return (new CasoResource($caso))->response();
    }

    /**
     * @group Casos
     *
     * Actualiza los datos de un caso y sus abogados asignados.
     *
     * @authenticated
     *
     * @urlParam caso integer required ID del caso. Example: 1
     *
     * @bodyParam numero_expediente string required Número de expediente, único. Example: EXP-0002-2024
     * @bodyParam cliente_id integer required ID del cliente. Example: 1
     * @bodyParam fecha_inicio date required Fecha de inicio del caso. Example: 2024-03-01
     * @bodyParam fecha_finalizacion date Fecha de finalización (si aplica). Example: 2024-09-01
     * @bodyParam estado string required Estado del caso. Enum: en_tramite, archivado, sentenciado, desistido, suspendido. Example: en_tramite
     * @bodyParam descripcion string Descripción del caso. Example: Contrato incumplido
     * @bodyParam abogados array<int> Lista de IDs de abogados asignados. Example: [1, 2]
     */
    public function update(UpdateCasoRequest $request, Caso $caso): JsonResponse
    {
        return (new CasoResource($this->service->update($caso, $request->validated())))->response();
    }

    /**
     * @group Casos
     *
     * Elimina un caso de forma lógica (soft delete).
     *
     * @authenticated
     *
     * @urlParam caso integer required ID del caso. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "message": "Caso eliminado."
     * }
     */
    public function destroy(Caso $caso): JsonResponse
    {
        $this->service->delete($caso);

        return response()->json([
            'message' => 'Caso eliminado.',
        ]);
    }
}
