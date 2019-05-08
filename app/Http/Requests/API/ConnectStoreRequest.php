<?php

namespace App\Http\Requests\API;

class ConnectStoreRequest extends MainFormRequest
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
            'match' => 'required|integer|in:0,1',
            'owner_id' => 'required|integer'
        ];
    }
}
