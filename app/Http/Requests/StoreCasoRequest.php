<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CasoEstado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación para la creación de casos.
 */
class StoreCasoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'numero_expediente' => ['required', 'string', 'max:30', 'unique:casos,numero_expediente'],
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_finalizacion' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', Rule::enum(CasoEstado::class)],
            'descripcion' => ['nullable', 'string'],
            'abogados' => ['nullable', 'array'],
            'abogados.*' => ['integer', 'exists:abogados,id'],
        ];
    }
}
