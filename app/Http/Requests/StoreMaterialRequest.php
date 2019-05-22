<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'image'        => 'required|image|max:2000',
            'title'        => 'required|max:15',
            'short_text'   => 'required|max:50',
            'full_text'    => 'required',
            'address'      => 'required',
            'phone_number' => 'required|min:14',
            'website'      => 'required|url',
        ];
    }
}
