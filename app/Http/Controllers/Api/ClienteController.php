<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function __construct(private readonly ClienteService $service) {}

    /**
     * @group Clientes
     *
     * Lista todos los clientes ordenados alfabéticamente.
     *
     * @authenticated
     *
     * @responseField data array Lista de clientes.
     *
     * @response status=200 scenario="success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "cedula": "1234567890",
     *       "nombre": "Ana",
     *       "apellido": "Gómez",
     *       "nombre_completo": "Ana Gómez",
     *       "email": "ana@example.com",
     *       "telefono": "3001234567",
     *       "direccion": "Calle 1 #2-3"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        return ClienteResource::collection($this->service->list())->response();
    }

    /**
     * @group Clientes
     *
     * Crea un nuevo cliente.
     *
     * @authenticated
     *
     * @bodyParam cedula string required Cédula de identidad, única. Example: 9876543210
     * @bodyParam nombre string required Nombre del cliente. Example: Ana
     * @bodyParam apellido string required Apellido del cliente. Example: Gómez
     * @bodyParam email string Email del cliente. Example: ana@example.com
     * @bodyParam telefono string Teléfono del cliente. Example: 3001234567
     * @bodyParam direccion string Dirección del cliente. Example: Calle 1 #2-3
     *
     * @response status=201 scenario="success" {
     *   "data": {
     *     "id": 2,
     *     "cedula": "9876543210",
     *     "nombre": "Ana",
     *     "apellido": "Gómez",
     *     "nombre_completo": "Ana Gómez",
     *     "email": "ana@example.com",
     *     "telefono": "3001234567",
     *     "direccion": "Calle 1 #2-3"
     *   }
     * }
     */
    public function store(StoreClienteRequest $request): JsonResponse
    {
        return (new ClienteResource($this->service->create($request->validated())))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * @group Clientes
     *
     * Devuelve la información de un cliente.
     *
     * @authenticated
     *
     * @urlParam cliente integer required ID del cliente. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "data": {
     *     "id": 1,
     *     "cedula": "1234567890",
     *     "nombre": "Ana",
     *     "apellido": "Gómez",
     *     "nombre_completo": "Ana Gómez",
     *     "email": "ana@example.com",
     *     "telefono": "3001234567",
     *     "direccion": "Calle 1 #2-3"
     *   }
     * }
     */
    public function show(Cliente $cliente): JsonResponse
    {
        return (new ClienteResource($cliente))->response();
    }

    /**
     * @group Clientes
     *
     * Actualiza los datos de un cliente.
     *
     * @authenticated
     *
     * @urlParam cliente integer required ID del cliente. Example: 1
     *
     * @bodyParam cedula string required Cédula de identidad, única. Example: 9876543210
     * @bodyParam nombre string required Nombre del cliente. Example: Ana
     * @bodyParam apellido string required Apellido del cliente. Example: Gómez
     * @bodyParam email string Email del cliente. Example: ana@example.com
     * @bodyParam telefono string Teléfono del cliente. Example: 3001234567
     * @bodyParam direccion string Dirección del cliente. Example: Calle 1 #2-3
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente): JsonResponse
    {
        return (new ClienteResource($this->service->update($cliente, $request->validated())))->response();
    }

    /**
     * @group Clientes
     *
     * Elimina un cliente de forma lógica (soft delete).
     *
     * @authenticated
     *
     * @urlParam cliente integer required ID del cliente. Example: 1
     *
     * @response status=200 scenario="success" {
     *   "message": "Cliente eliminado."
     * }
     */
    public function destroy(Cliente $cliente): JsonResponse
    {
        $this->service->delete($cliente);

        return response()->json([
            'message' => 'Cliente eliminado.',
        ]);
    }
}
