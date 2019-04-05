<?php

namespace App;

use App\Components\Traits\Models\PetFriends;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use PetFriends;

    public $fillable = [
        'owner_id', 'name', 'gender',
        'size', 'primary_breed', 'secondary_breed',
        'age', 'profile_picture', 'friendliness',
        'activity_level', 'noise_level', 'odebience_level',
        'fetchability', 'swimability', 'like',
        'dislike', 'favorite_toys', 'fears',
        'favorite_places', 'spayed', 'birthday',
        'city', 'state',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function pictures()
    {
        return $this->morphMany(Picture::class, 'picturable');
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'pet_chat_room')
            ->withPivot(['is_read'])
            ->with(['pets' => function ($query) {
                return $query->where('id', '<>', $this->id);
            }]);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function eventInvites()
    {
        return $this->hasMany(EventInvite::class);
    }

    public function invitedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_invites');
    }

    public function getEventsByDates($fromDate, $toDate)
    {
        $petEvents = $this->events()
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->where(function ($q) use ($fromDate, $toDate) {
                    return $q->whereDate('from_date', '>=', $fromDate)->whereDate('from_date', '<=', $toDate);
                })->orWhere('repeat', '<>', '[]');
            })
            ->get();
        $invitedEvents = $this->eventInvites()
            ->with('event')
            ->whereHas('event', function ($query) use ($fromDate, $toDate) {
                $query->where(function ($query) use ($fromDate, $toDate) {
                    return $query->whereDate('from_date', '>=', $fromDate)->whereDate('from_date', '<=', $toDate);
                })->orWhere('repeat', '<>', '[]');
            })
            ->where('accepted', true)
            ->get()
            ->pluck('event');

        $allEvents = $petEvents->merge($invitedEvents);
        $eventsArr = [];

        foreach ($allEvents as $event) {
            if(strtotime($event->from_date) < strtotime($fromDate)){
                $fromDateCarbon = Carbon::parse($fromDate);
            }elseif(strtotime($event->from_date) > strtotime($toDate)){
                continue;
            }else{
                $fromDateCarbon = Carbon::parse($event->from_date);

                if(!isset($eventsArr[$fromDateCarbon->format('Y-m-d')]))
                    $eventsArr[$fromDateCarbon->format('Y-m-d')] = [];

                $eventsArr[$fromDateCarbon->format('Y-m-d')][$event->id] = $event;
            }

            $formatFromDate = $fromDateCarbon->format('Y-m-d');

            if($event->repeat){
                $repeats = collect($event->repeat)->sort();

                $dayOfWeek = $fromDateCarbon->dayOfWeek;
                $dayOfWeek = $dayOfWeek == 7 ? 1 : $dayOfWeek + 1;

                $interval = '7 days';
                $endDate = $toDate;
                foreach ($repeats as $repeat) {
                    $fromDateCarbonIteration = Carbon::parse($formatFromDate);
                    if($repeat < $dayOfWeek){
                        $fromDateCarbonIteration->addDays(7 - ($dayOfWeek - $repeat));
                    }elseif($repeat > $dayOfWeek){
                        $fromDateCarbonIteration->addDays($repeat - $dayOfWeek);
                    }
                    $startDate = $fromDateCarbonIteration->format('Y-m-d');

                    $period = CarbonPeriod::create($startDate, $interval, $endDate);
                    foreach ($period as $date) {
                        $formatDate = $date->format('Y-m-d');
                        if(!isset($eventsArr[$formatDate]))
                            $eventsArr[$formatDate] = [];

                        $eventsArr[$formatDate][$event->id] = $event;
                    }
                }
            }
        }

        ksort($eventsArr);
        return $eventsArr;
    }
}
