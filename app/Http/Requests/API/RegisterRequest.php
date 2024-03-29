<?php

namespace App\Http\Requests\API;

class RegisterRequest extends MainFormRequest
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
            'email' => 'required|email|max:128|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:10|max:32|confirmed|regex:~^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{10,32}$~',
            'password_confirmation' => 'required',
            'utc' => 'required|integer|min:-12|max:12'
        ];
    }
}
