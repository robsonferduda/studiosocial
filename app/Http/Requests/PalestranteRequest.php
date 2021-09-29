<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PalestranteRequest extends FormRequest
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
            'ds_email_pes' => 'required',
            'nm_pessoa_pes' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'ds_email_pes.required' => 'Campo <strong>Email</strong> é obrigatório',
            'nm_pessoa_pes.required' => 'Campo <strong>Nome</strong> é obrigatório'
        ];
    }
}
