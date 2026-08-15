<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function __construct(private readonly ClienteService $service) {}

    /**
     * Listado paginado de clientes, con búsqueda opcional por el parámetro "q".
     */
    public function index(Request $request): View
    {
        return view('clientes.index', [
            'clientes' => $this->service->list($request->query('q')),
        ]);
    }

    /**
     * Formulario de creación.
     */
    public function create(): View
    {
        return view('clientes.create');
    }

    /**
     * Persiste un nuevo cliente.
     */
    public function store(StoreClienteRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Detalle de un cliente.
     */
    public function show(Cliente $cliente): View
    {
        $cliente->loadMissing('casos');

        return view('clientes.show', ['cliente' => $cliente]);
    }

    /**
     * Formulario de edición.
     */
    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', ['cliente' => $cliente]);
    }

    /**
     * Actualiza los datos de un cliente.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $this->service->update($cliente, $request->validated());

        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Elimina un cliente de forma lógica.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $this->service->delete($cliente);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
