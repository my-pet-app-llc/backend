<?php

namespace App\Http\Requests\API;

class RegisterFbRequest extends MainFormRequest
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
            'facebook_id' => 'required|unique:users,facebook_id,NULL,id,deleted_at,NOT_NULL',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'utc' => 'required|integer|min:-12|max:12'
        ];
    }

    protected function validationData()
    {
        $user = $this->get('fb_user');
        $this->merge([
            'email' => $user->email,
            'facebook_id' => $user->id
        ]);

        return parent::validationData();
    }
}
