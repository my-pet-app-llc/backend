<?php

namespace App\Rules;

use App\Pet;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class EventInviteRule implements Rule
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Pet
     */
    private $pet;

    /**
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->pet = $request->user()->pet()->with('friends')->first();
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
        if(!$value)
            return true;

        if(!is_array($value)){
            $this->message = 'Friends inviting field must be an array.';
            return false;
        }

        $invitingFriends = array_unique($value);
        $existsFriends = $this->pet->friends->pluck('friend_id')->toArray();

        if(!empty(array_diff($invitingFriends, $existsFriends))){
            $this->message = 'Some pets is not a your friends.';
            return false;
        }

        if($this->request->route()->hasParameter('event')){
            $event = $this->request->route('event');
            $existsInvitedFriends = $event->eventInvites()->whereIn('pet_id', $invitingFriends)->where(function ($query) {
                return $query->whereNull('accepted')->orWhere('accepted', true);
            })->get();
            if($existsInvitedFriends->count()){
                $this->message = 'Some friends already invited for this event.';
                return false;
            }
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
        return $this->message;
    }
}
