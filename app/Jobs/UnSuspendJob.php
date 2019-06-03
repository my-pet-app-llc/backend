<?php

namespace App\Jobs;

use App\Owner;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UnSuspendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ownerId;

    /**
     * Create a new job instance.
     *
     * @param int $ownerId
     */
    public function __construct(int $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $owner = Owner::query()->find($this->ownerId);
        $jobId = $this->job->getJobId();
        if($owner && $owner->suspended_job_id == $jobId && $owner->status == Owner::STATUS['suspended']){
            $owner->reloadStatus(true);
        }
    }
}
