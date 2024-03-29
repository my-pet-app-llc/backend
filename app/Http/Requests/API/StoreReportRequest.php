<?php

namespace App\Http\Requests\API;

class StoreReportRequest extends MainFormRequest
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
            'reason' => 'required|string|min:1|max:255',
            'pet_id' => 'required|exists:pets,id'
        ];
    }
}
