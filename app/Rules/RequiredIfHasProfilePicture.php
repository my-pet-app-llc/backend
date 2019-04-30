<?php

namespace App\Rules;

use App\Http\Requests\API\MainFormRequest;
use Illuminate\Contracts\Validation\Rule;

class RequiredIfHasProfilePicture implements Rule
{
    private $user;

    private $model;

    /**
     * Create a new rule instance.
     *
     * @param MainFormRequest $request
     * @param string $model
     */
    public function __construct(MainFormRequest $request, string $model)
    {
        $this->user = $request->user();
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->model == 'owner'){
            $profilePicture = $this->user->owner->profile_picture;
        }elseif($this->model == 'pet'){
            $profilePicture = $this->user->owner->pet->profile_picture;
        }else{
            return true;
        }

        if(!$profilePicture && !$value)
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Profile picture is required.';
    }
}
