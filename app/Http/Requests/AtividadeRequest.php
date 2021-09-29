<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtividadeRequest extends FormRequest
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
            'id_evento_eve' => 'required',
            'id_sala_sal' => 'required',
            'id_tipo_atividade_tia' => 'required',
            'nm_atividade_ati' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_evento_eve.required' => 'Campo <strong>Evento</strong> é obrigatório',
            'id_sala_sal.required' => 'Campo <strong>Local/Sala</strong> é obrigatório',
            'id_tipo_atividade_tia.required' => 'Campo <strong>Tipo de Atividade</strong> é obrigatório',
            'nm_atividade_ati.required' => 'Campo <strong>Título</strong> é obrigatório'
        ];
    }
}