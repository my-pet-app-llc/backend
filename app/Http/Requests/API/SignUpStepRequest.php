<?php

namespace App\Http\Requests\API;

use App\Rules\RequiredIfHasProfilePicture;
use App\Rules\SignUpMaxStep;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SignUpStepRequest extends MainFormRequest
{
    /**
     * Rules for all steps
     *
     * @var array
     */
    public $rules = [];

    /**
     * Max steps count
     *
     * @var integer
     */
    const MAX_STEP = 13;

    /**
     * Steps has pictures
     *
     * @var array
     */
    const PICTURE_STEPS = [
        'pet.profile_picture' => 4,
        'pet.pictures' => 5,
        'owner.profile_picture' => 12
    ];

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
        if($this->isMethod('put')){
            $step = $this->get('step');

            if (array_key_exists($step, $this->rules) === false && $step != 0)
            {
                throw new NotFoundHttpException('Step not found.');
            }

            $rules = isset($this->rules[$step]) ? $this->rules[$step] : [];
            $rules['step'] = [
                (new SignUpMaxStep($this))
            ];
        }else{
            $rules = [];
        }

        return $rules;
    }

    protected function validationData ()
    {
        $this->rules = [
            1 => [
                'owner.first_name' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'owner.last_name' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'pet.name' => 'required|string|min:1|max:12|regex:~^([[:alpha:]-]+\s?)+$~',
                'owner.city' => 'required|string|min:1|max:15|regex:~^([[:alpha:]-]+\s?)+$~',
                'owner.state' => 'required|string|min:2|max:3|regex:~^[A-Z]{2,3}$~'
            ],
            2 => [
                'pet.gender' => 'required|string|in:male,female',
                'pet.size' => 'required|string|in:small,medium,large,giant'
            ],
            3 => [
                'pet.primary_breed' => 'required|string|min:1|max:50',
                'pet.secondary_breed' => 'required|string|min:1|max:50',
                'pet.age' => 'required|integer|min:0|max:99'
            ],
            4 => [
                'pet.profile_picture' => ['nullable', (new RequiredIfHasProfilePicture($this, 'pet')), 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~']
            ],
            5 => [
                'pet.pictures' => 'nullable|array',
                'pet.pictures.create.*' => ['required', 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~'],
                'pet.pictures.delete.*' => 'required|integer|exists:pictures,id'
            ],
            6 => [
                'pet.friendliness' => 'required|integer|min:1|max:5',
                'pet.activity_level' => 'required|integer|min:1|max:5',
                'pet.noise_level' => 'required|integer|min:1|max:5',
                'pet.odebience_level' => 'required|integer|min:1|max:5',
                'pet.fetchability' => 'required|integer|min:1|max:5',
                'pet.swimability' => 'required|integer|min:1|max:5'
            ],
            7 => [],
            8 => [
                'pet.like' => 'required|string|min:1|max:128',
                'pet.dislike' => 'required|string|min:1|max:128'
            ],
            9 => [
                'pet.favorite_toys' => 'required|string|min:1|max:128',
                'pet.fears' => 'required|string|min:1|max:128'
            ],
            10 => [
                'pet.favorite_places' => 'required|string|min:1|max:128'
            ],
            11 => [
                'owner.gender' => ['required', 'string', 'min:1', 'max:20', 'regex:~^(male|female|[[:alpha:]]+)$~'],
                'owner.age' => 'required|integer|min:0|max:99'
            ],
            12 => [
                'owner.profile_picture' => ['nullable', (new RequiredIfHasProfilePicture($this, 'pet')), 'string', 'regex:~^(data:image\/(jpeg|png);base64,\S+)$~']
            ],
            13 => []
        ];

        return parent::validationData();
    }
}
