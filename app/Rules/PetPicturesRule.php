<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PetPicturesRule implements Rule
{
    const MAX_PICTURES = 6;

    private $message;

    private $validate = true;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $picturesCount = auth()->user()->owner->pet->pictures->count();
        $createPictures = isset($value['create']) ? $value['create'] : [];
        $deletePictures = isset($value['delete']) ? $value['delete'] : [];

        if($createPictures && $picturesCount >= self::MAX_PICTURES)
            $this->validationFail('Maximum limit of pictures exhausted.');

        if($this->validate && ($picturesCount + count($createPictures) - count($deletePictures)) > self::MAX_PICTURES)
            $this->validationFail("The total number of uploaded pictures should not be more than " . self::MAX_PICTURES);

        if($this->validate && ((!$picturesCount && !count($createPictures)) || $picturesCount <= count($deletePictures)))
            $this->validationFail('There must be at least one picture');

        return $this->validate;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    private function validationFail($message = 'Invalid data.')
    {
        $this->message = $message;
        $this->validate = false;
    }
}
