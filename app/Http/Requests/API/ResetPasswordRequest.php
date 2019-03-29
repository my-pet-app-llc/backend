<?php

namespace App\Http\Requests\API;

class ResetPasswordRequest extends MainFormRequest
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
            'token' => 'required|string',
            'email' => 'required|exists:users,email',
            'password' => 'required|string|min:8|max:128|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
