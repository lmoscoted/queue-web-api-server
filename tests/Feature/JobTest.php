<?php

namespace Tests\Feature;

use App\Jobs\ExecuteCommand;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class JobTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    private $priority = 'default';
    private $submiter_id = 5;


    public function testNothingPushed()
    {
        $queue = Queue::fake();
        // Assert that no jobs were pushed...
        $queue->assertNothingPushed();

    }

    public function testJobPushedOnQueueDefault()
    {
        $queue = Queue::fake();
        $job = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);

        $JobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job);

        // Assert a job was pushed to the default queue
        Queue::assertPushedOn($this->priority,ExecuteCommand::class);

    }
    public function testJobPushedOnQueueHigh()
    {
        $queue = Queue::fake();
        $this->priority = 'high';
        $job = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);

        $JobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job);
        // Assert a job was pushed to the high queue
        Queue::assertPushedOn($this->priority,ExecuteCommand::class);

    }

    public function testJobPushedOnQueueLow()
    {
        $queue = Queue::fake();
        $this->priority = 'low';
        $job = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);

        $JobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job);

        // Assert a job was pushed to the low queue
        Queue::assertPushedOn($this->priority,ExecuteCommand::class);

    }


    public function testJobWithCorrectSubmiterId()
    {
        $queue = Queue::fake();
        $this->priority = 'low';
        $job = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);

        $JobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job);
        // Assert a job was pushed with the correct submiter_id
        Queue::assertPushed(function (ExecuteCommand $job)  {
            return $job->submiter_id === $this->submiter_id;
        });

    }

    public function testClientCanSubmitMoreThanOneJob()
    {
        $queue = Queue::fake();
        $this->priority = 'low';
        // Generate first job
        $job1 = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);
        // Dispatch job1
        app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job1);
        // Generate second job
        $job2 = (new ExecuteCommand($this->submiter_id))
                ->onQueue($this->priority);
        // Dispatch job
        app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job2);

        // Assert a two jobs have the same submiter_id (client)
        Queue::assertPushed(function (ExecuteCommand $job1) use ($job2)  {
            return $job1->submiter_id && $job2->submiter_id === $this->submiter_id;
        });

    }

}
