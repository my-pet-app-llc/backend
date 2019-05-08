<?php

namespace App\Http\Requests\API;

class FriendRequestUpdateRequest extends MainFormRequest
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
            'accept' => 'required|integer|in:0,1'
        ];
    }
}
