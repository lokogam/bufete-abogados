<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbogadoRequest;
use App\Http\Requests\UpdateAbogadoRequest;
use App\Models\Abogado;
use App\Services\AbogadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbogadoController extends Controller
{
    public function __construct(private readonly AbogadoService $service) {}

    /**
     * Listado paginado de abogados, con búsqueda opcional por el parámetro "q".
     */
    public function index(Request $request): View
    {
        return view('abogados.index', [
            'abogados' => $this->service->list($request->query('q')),
        ]);
    }

    /**
     * Formulario de creación.
     */
    public function create(): View
    {
        return view('abogados.create');
    }

    /**
     * Persiste un nuevo abogado.
     */
    public function store(StoreAbogadoRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('abogados.index')->with('success', 'Abogado registrado correctamente.');
    }

    /**
     * Detalle de un abogado.
     */
    public function show(Abogado $abogado): View
    {
        $abogado->loadMissing('casos');

        return view('abogados.show', ['abogado' => $abogado]);
    }

    /**
     * Formulario de edición.
     */
    public function edit(Abogado $abogado): View
    {
        return view('abogados.edit', ['abogado' => $abogado]);
    }

    /**
     * Actualiza los datos de un abogado.
     */
    public function update(UpdateAbogadoRequest $request, Abogado $abogado): RedirectResponse
    {
        $this->service->update($abogado, $request->validated());

        return redirect()->route('abogados.show', $abogado)->with('success', 'Abogado actualizado correctamente.');
    }

    /**
     * Elimina un abogado de forma lógica.
     */
    public function destroy(Abogado $abogado): RedirectResponse
    {
        $this->service->delete($abogado);

        return redirect()->route('abogados.index')->with('success', 'Abogado eliminado correctamente.');
    }
}
