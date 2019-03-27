<?php

namespace App\Rules;

use App\Http\Requests\API\SignUpStepRequest;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class SignUpMaxStep implements Rule
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * @var array|null
     */
    private $rules;

    /**
     * Create a new rule instance.
     *
     * @param SignUpStepRequest $request
     */
    public function __construct(SignUpStepRequest $request)
    {
        $this->user = $request->user();
        $this->rules = $request->rules;
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
        $step = $this->user->owner->signup_step;
        if($value > 0){
            $user_step = $this->getUserStep($step);

            if($value > $user_step)
                return false;
        }elseif($step != SignUpStepRequest::MAX_STEP){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Previous steps must be done.';
    }

    /**
     * @param int $step
     * @return int
     */
    private function getUserStep (int $step) : int
    {
        if($this->rules[$step] == [])
            return $this->getUserStep($step + 1);
        else
            return $step;
    }
}
