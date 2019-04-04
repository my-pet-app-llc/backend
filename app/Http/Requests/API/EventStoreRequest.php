<?php

namespace App\Http\Requests\API;

use App\Event;
use App\Rules\CheckTimeRule;
use App\Rules\EventInviteRule;

class EventStoreRequest extends MainFormRequest
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
        $types = implode(',', array_keys(Event::TYPES));

        $rules = [
            'name' => 'required|string|min:1|max:15',
            'type' => 'required|in:' . $types,
            'from_date' => 'required|date_format:Y-m-d|after:yesterday',
            'from_time' => 'required|regex:~^[0-9][0-9]:[0-9][0-9]$~',
            'to_time' => ['required', 'regex:~^[0-9][0-9]:[0-9][0-9]$~', (new CheckTimeRule($this, 'from_date', 'from_time'))],
            'repeat' => 'nullable|array|max:7',
            'repeat.*' => 'required|min:1|max:7',
            'where' => 'required|string|min:1|max:40',
            'notes' => 'nullable|string|min:1|max:128',
            'invite' => [(new EventInviteRule($this))],
        ];

        return $rules;
    }
}
