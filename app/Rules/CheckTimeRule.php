<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CheckTimeRule implements Rule
{
    private $request;

    private $dateField;

    private $fromTimeField;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     * @param string $dateField
     * @param string|null $fromTimeField
     */
    public function __construct(Request $request, string $dateField, string $fromTimeField = null)
    {
        $this->request = $request;
        $this->dateField = $dateField;
        $this->fromTimeField = $fromTimeField;
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
        $date = $this->request->get($this->dateField);

        if(!$date)
            return true;

        $date = Carbon::parse($date);

        if(!$date->isValid())
            return true;

        $date = $date->format('Y-m-d');

        if($this->fromTimeField){
            $time = $this->request->get($this->fromTimeField);
            $result = $this->fromToValidate($date, $time, $value);
        }else{
            $result = $this->validateForNow($date, $value);
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Time not valid.';
    }

    private function fromToValidate($date, $fromTime, $toTime)
    {
        if(!$this->validateForNow($date, $fromTime))
            return false;

        $from_date = Carbon::parse($date.$fromTime)->toDateTimeString();
        $to_date = Carbon::parse($date.$toTime)->toDateTimeString();

        return strtotime($from_date) < strtotime($to_date);
    }

    private function validateForNow($date, $time)
    {
        $now = Carbon::now()->toDateTimeString();
        $date = Carbon::parse($date.$time)->toDateTimeString();

        return strtotime($now) < strtotime($date);
    }
}
