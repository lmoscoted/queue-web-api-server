<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

# Get the job status
Route::get('job/{id}', 'JobController@show');#->where('id','[0-9]+');
# Add a job to the queue
Route::post('job', 'JobController@store');
# Get the average runtime
Route::get('job/average/runtime', 'JobController@getAverageRuntime');




