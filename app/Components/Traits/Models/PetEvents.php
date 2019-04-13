<?php


namespace App\Components\Traits\Models;


use App\Event;
use App\Http\Resources\EventWithoutInvitesResource;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait PetEvents
{
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

    public function reminder()
    {
        $now = Carbon::now();
        $from = $now->format('Y-m-d H-i-s');
        $fromDay = $now->dayOfWeek - 1;
        $now->addHours(24);
        $toCare = $now->format('Y-m-d H-i-s');
        $toCareDay = $now->dayOfWeek - 1;
        $now->addHours(24);
        $toSocial = $now->format('Y-m-d H-i-s');
        $toSocialDay = $now->dayOfWeek - 1;

        $query = function ($query) use ($from, $toSocial, $toCare) {
            return $query->where(function ($q) use ($from, $toSocial) {
                return $q->whereRaw("CONCAT(from_date, ' ', from_time) >= ?", [$from])
                    ->whereRaw("CONCAT(from_date, ' ', from_time) <= ?", [$toSocial])
                    ->where('type', Event::TYPES['social']);
            })->orWhere(function ($q) use ($from, $toCare) {
                return $q->whereRaw("CONCAT(from_date, ' ', from_time) >= ?", [$from])
                    ->whereRaw("CONCAT(from_date, ' ', from_time) <= ?", [$toCare])
                    ->where('type', Event::TYPES['care']);
            })->orWhere(function ($q) use ($toCare, $toSocial) {
                return $q->where('repeat', '<>', '[]')
                    ->where(function ($q) use ($toSocial, $toCare) {
                        return $q->where(function ($q) use ($toSocial) {
                            return $q->whereRaw("CONCAT(from_date, ' ', from_time) <= ?", [$toSocial])
                                ->where('type', Event::TYPES['social']);
                        })->orWhere(function ($q) use ($toCare) {
                            return $q->whereRaw("CONCAT(from_date, ' ', from_time) <= ?", [$toCare])
                                ->where('type', Event::TYPES['care']);
                        });
                    });
            });
        };

        $petEvents = $this->events()
            ->where($query)
            ->get();
        $invitedEvents = $this->eventInvites()
            ->with('event')
            ->whereHas('event', $query)
            ->where('accepted', true)
            ->get()
            ->pluck('event');

        $allEvents = $petEvents->merge($invitedEvents);

        $events = [];
        $days = [
            'social' => ['from' => $fromDay, 'social' => $toSocialDay],
            'care' => ['from' => $fromDay, 'social' => $toSocialDay, 'care' => $toCareDay]
        ];
        $dateTimes = [
            'from' => $from, 'social' => $toSocial, 'care' => $toCare
        ];
        foreach ($allEvents as $event) {
            if(!$event->repeat){
                $events[$event->from_date][] = $event;
                continue;
            }else{
                foreach ($event->repeat as $repeat) {
                    $type = array_search($event->type, Event::TYPES);
                    $eventDays = array_search($repeat, $days[$type]);
                    if($eventDays === false)
                        continue;

                    $eventDateTime = Carbon::parse($dateTimes[$eventDays])->format('Y-m-d');
                    $eventDateTime = $eventDateTime . ' ' . $event->from_time;
                    if($eventDays == 'from' && strtotime($eventDateTime) < strtotime($dateTimes[$eventDays])){
                        continue;
                    }elseif(strtotime($eventDateTime) > strtotime($dateTimes[$eventDays])){
                        continue;
                    }else{
                        $events[Carbon::parse($dateTimes[$eventDays])->format('Y-m-d')][] = new EventWithoutInvitesResource($event);
                    }
                }
            }
        }

        ksort($events);
        return collect($events);
    }
}