<?php

namespace App\Http\Requests\API;

class LocationRequest extends MainFormRequest
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
            'lng' => 'required|numeric|min:-180|max:180',
            'lat' => 'required|numeric|min:-90|max:90'
        ];
    }
}
