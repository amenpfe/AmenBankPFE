<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptRequestDetailsRequest extends FormRequest
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
            'reques_id' => 'exists:requests,id',
            'chd_doc' => 'required|file|mimes:pdf'
        ];
    }
}
