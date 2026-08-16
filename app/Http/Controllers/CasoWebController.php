<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CasoEstado;
use App\Http\Requests\StoreCasoRequest;
use App\Http\Requests\UpdateCasoRequest;
use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use App\Services\CasoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CasoWebController extends Controller
{
    public function __construct(private readonly CasoService $service) {}

    /**
     * Listado paginado de casos en orden ascendente por expediente,
     * con búsqueda opcional por "q" y filtro por estado con "estado".
     */
    public function index(Request $request): View
    {
        return view('casos.index', [
            'casos' => $this->service->list(
                search: $request->query('q'),
                estado: $request->query('estado'),
            ),
            'estados' => CasoEstado::cases(),
        ]);
    }

    /**
     * Formulario de creación de un caso.
     */
    public function create(): View
    {
        return view('casos.create', $this->formData());
    }

    /**
     * Persiste un nuevo caso.
     */
    public function store(StoreCasoRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('casos.index')->with('success', 'Caso registrado correctamente.');
    }

    /**
     * Detalle completo de un caso.
     */
    public function show(Caso $caso): View
    {
        $caso->load(['cliente', 'abogados']);

        return view('casos.show', ['caso' => $caso]);
    }

    /**
     * Formulario de edición de un caso.
     */
    public function edit(Caso $caso): View
    {
        $caso->loadMissing('abogados');

        return view('casos.edit', array_merge($this->formData(), ['caso' => $caso]));
    }

    /**
     * Actualiza los datos de un caso.
     */
    public function update(UpdateCasoRequest $request, Caso $caso): RedirectResponse
    {
        $this->service->update($caso, $request->validated());

        return redirect()->route('casos.show', $caso)->with('success', 'Caso actualizado correctamente.');
    }

    /**
     * Elimina un caso de forma lógica.
     */
    public function destroy(Caso $caso): RedirectResponse
    {
        $this->service->delete($caso);

        return redirect()->route('casos.index')->with('success', 'Caso eliminado correctamente.');
    }

    /**
     * Datos compartidos por los formularios de casos.
     *
     * @return array{clientes: mixed, abogados: mixed, estados: array<string, string>}
     */
    private function formData(): array
    {
        return [
            'clientes' => Cliente::query()->orderBy('apellido')->orderBy('nombre')->get(),
            'abogados' => Abogado::query()->orderBy('apellido')->orderBy('nombre')->get(),
            'estados' => CasoEstado::labels(),
        ];
    }
}
