<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeRangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Adicionamos todos os periodos suportados
            'tempo' => [
                'required',
                'string',
                'in:hoje,ontem,semana-atual,semana-passada,mes-atual,mes-passado,periodo'
            ],

            // As datas são obrigatórias APENAS se tempo for 'periodo'
            'inicio' => [
                'required_if:tempo,periodo',
                'nullable',
                'date',
                'date_format:Y-m-d'
            ],
            'fim' => [
                'required_if:tempo,periodo',
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:inicio' // Garante que o fim não seja antes do início
            ],
        ];
    }

    public function prepareForValidation()
    {
        // Se 'tempo' for nulo, definimos o padrão como 'hoje'
        $this->merge([
            'tempo' => $this->tempo ?? 'hoje'
        ]);
    }
}
