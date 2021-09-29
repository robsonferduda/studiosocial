<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipanteImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'arquivo' => 'required|mimes:csv,txt',
            'evento' => 'required|min:1'
        ];
    }

    public function messages()
    {
        return [
            'arquivo.required' => 'Campo <strong>Planilha de Dados</strong> é obrigatório',
            'evento.required' => 'Campo <strong>Evento</strong> é obrigatório',
            'evento.min' => 'Campo <strong>Evento</strong> é obrigatório'
        ];
    }
}
