<?php

namespace App\Listeners\Owners;

use App\Components\Interfaces\ShouldReportEvent;
use App\Jobs\UnSuspendJob;
use App\Owner;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class StartSuspendTimer
{
    use DispatchesJobs;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ShouldReportEvent $event
     * @return void
     */
    public function handle(ShouldReportEvent $event)
    {
        $owner = $event->getOwner();
//        $outOfSuspend = Carbon::now()->addMinutes(Owner::SUSPENDED_TIME);
        $outOfSuspend = Carbon::now()->addSeconds(30);

        $unsuspendJob = new UnSuspendJob($owner->id);
        $unsuspendJob->delay($outOfSuspend);
        $jobId = $this->dispatch($unsuspendJob);

        $owner->suspended_to = $outOfSuspend;
        $owner->suspended_job_id = $jobId;
    }
}
