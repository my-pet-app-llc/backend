<?php

namespace App\Http\Requests\API;

use App\Rules\RequiredIfHasProfilePicture;
use Carbon\Carbon;

class ProfileUpdateRequest extends MainFormRequest
{
    private $rules = [];

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
        return $this->rules;
    }

    protected function validationData()
    {
        if($this->isMethod('put')){
            $birthdayMax = Carbon::now()->subDay()->format('Y-m-d');
            $this->rules = [
                'owner.first_name' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'owner.last_name' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'owner.gender' => ['required', 'string', 'min:1', 'max:20', 'regex:~^(male|female|[[:alpha:]]+)$~'],
                'owner.birthday' => 'required|date_format:"Y-m-d"|before:' . $birthdayMax,
                'owner.occupation' => 'required|string|min:1|max:128',
                'owner.hobbies' => 'required|string|min:1|max:128',
                'owner.favorite_park' => 'required|string|min:1|max:128',
                'owner.pets_owned' => 'required|string|min:1|max:128',
                'owner.profile_picture' => ['nullable', (new RequiredIfHasProfilePicture($this, 'owner')), 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~'],
                'pet.friendliness' => 'required|integer|min:1|max:5',
                'pet.activity_level' => 'required|integer|min:1|max:5',
                'pet.noise_level' => 'required|integer|min:1|max:5',
                'pet.odebience_level' => 'required|integer|min:1|max:5',
                'pet.fetchability' => 'required|integer|min:1|max:5',
                'pet.swimability' => 'required|integer|min:1|max:5',
                'pet.name' => 'required|string|min:1|max:12|regex:~^([[:alpha:]-]+\s?)+$~',
                'pet.gender' => 'required|string|in:male,female',
                'pet.spayed' => 'required|in:1,0',
                'pet.size' => 'required|string|in:small,medium,large,giant',
                'pet.age' => 'required|integer|min:0|max:99',
                'pet.birthday' => 'required|date_format:"Y-m-d"|before:' . $birthdayMax,
                'pet.city' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'pet.state' => 'required|string|min:2|max:3|regex:~^[A-Z]{2,3}$~',
                'pet.like' => 'required|string|min:1|max:128',
                'pet.dislike' => 'required|string|min:1|max:128',
                'pet.favorite_toys' => 'required|string|min:1|max:128',
                'pet.fears' => 'required|string|min:1|max:128',
                'pet.favorite_places' => 'required|string|min:1|max:128',
                'pet.profile_picture' => ['nullable', (new RequiredIfHasProfilePicture($this, 'pet')), 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~'],
                'pet.pictures' => 'nullable|array',
                'pet.pictures.create.*' => ['required', 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~'],
                'pet.pictures.delete.*' => 'required|integer|exists:pictures,id',
            ];
        }

        return parent::validationData();
    }
}
