<?php

namespace App\Http\Requests\API;

class AcceptInviteEventRequest extends MainFormRequest
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
            'invite_id' => 'required',
            'accept' => 'required|in:0,1'
        ];
    }
}
