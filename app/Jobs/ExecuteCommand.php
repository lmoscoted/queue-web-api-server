<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExecuteCommand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $submiter_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($submiter_id)
    {
        //
        $this->submiter_id = $submiter_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        # max time for job
        try {
            print($this->job->getJobId());
            // Simulate a job
            sleep(rand(1,30));

        } catch (\Throwable $th) {
            //Log errors
            Log::error($th->getMessage());
        }
    }

}
