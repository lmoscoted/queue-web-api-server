<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ExecuteCommand;
use Illuminate\Routing\Controller as BaseController;
use Cake\Chronos\Chronos;

class JobController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $JobId;
    // protected $redis = new Redis;

    # Get the job's status
    public function show($id)
    {
        $job_status = Redis::hget('laravel_horizon:'.$id, 'status');
        if ($job_status == false){

            return response()->json([
                'error' => 'Job '.$id.' not found'], 404);
        }
        return response()->json(['Job status' => $job_status], 200);
    }

    # Add job to the queue
    public function store(Request $request)
    {
        // VALIDATION
        $priority_values = ['high','default','low'];
        $validator = Validator::make($request->all(),
                    ['priority' => ['required',Rule::in($priority_values)],
                    'submiter_id' => ['required','numeric']]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }
        # Get both priority and submiter_id (client)
        $priority = $request->priority;
        $submiter_id = $request->submiter_id;


        $job = (new ExecuteCommand($submiter_id))
                ->onConnection('redis')
                ->onQueue($priority);

        $JobId = app(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch($job);
        return response()->json(['created'=> true,
                                 'id'=>$JobId], 201);
    }

    public function getAverageRuntime() {

        // key for redis
        $keyJob = 'laravel_horizon:snapshot:job:'.'App\Jobs\ExecuteCommand';
        $res = Redis::command('zrange',[$keyJob,'-1','-1']);
        if($res) {
            $metrics = json_decode($res[0]);
            return response()->json($metrics);
            }
        else{
            return response()->json([
                    'throughput' => false,
                    'runtime'    => false,
                    'time'       => false
            ]);
        }
    }

}
