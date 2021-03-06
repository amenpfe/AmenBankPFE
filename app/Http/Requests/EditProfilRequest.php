<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProfilRequest extends FormRequest
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
            "name" => "string|max:255|required",
            "adresse" => "string|max:255|required",
            "phone" => "integer|required",
            "email" => "email|max:255|required",
            "password" => "confirmed",
            "current_password" => "required|min:6"
        ];
    }
}
