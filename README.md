
# About this project

This is an implementation of a priority queue web-api server (REST API), that can be used to add “jobs” on a queue. Each job consists of a job id, submitter’s id, processor’s id (if its being processed) and a command to execute.

It was built with [PHP 7.3](https://www.php.ne), [Laravel 7.1](https://laravel.com/), [Redis](https://redis.io/) and [nginx](https://www.nginx.com/). It allows to process jobs based on priority (low, default and high). 

To simulate the execution of a job, a delay  in seconds was implemented with random values (1 to 30).

I set up 10 job processors with  Laravel Horizon as job manager.  One job processor may only process one job at a time and No two job processors should pick the same job.

# Installation

1. Clone the repository.  `git clone https://github.com/lmoscoted/queue-web-api-server.git`
2. Go to the project folder `cd queue-web-api-server/`
3. Create the laravel project environment file. `cp .env.example .env`
4.  go to laradock folder `cd laradock/`
5. Create the laradock environment file `cp .env-example .env`
6. Build the containers.  `docker-compose -f gmr-docker-compose.yml up -d nginx redis` 
7. Wait for docker building process finishes.
8. Go to the workspace container. run `docker-compose exec workspace bash`
9. Install the Laravel project. run `composer install` 
10. Generate the application key. `php artisan key:generate`
11. Start the jobs process manager. `php artisan horizon` 

**Note**: If the file laravel.log has permission issues, 
change the permision by running `chown laradock:laradock storage/logs/laravel.log`

## Testing
For testing, go to laradock folder, then run docker-composer exec workspace. Finally run php artisan test.
```
docker-compose exec workspace bash
php artisan test
```
### Load Testing
For load testing it will be used the data which located in the **data folder**. So those commands must be ran on that folder. We can use [Apache Benchmark](https://httpd.apache.org/docs/2.4/programs/ab.html)

Test with 1.000 concurrent requests until reach 4.000.

- `ab -n 4000 -c 1000 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/`

Test for get job status
- `ab -n 4000 -c 1000 -k -s 60 http://127.0.0.1/api/job/2000/`

For test priority queue, first add 50 jobs on low queue, then 50 on default and finally 50 on the high one.
- `ab -n 100 -c 50 -k  -s 60 -T application/json -p dataLow.json  http://127.0.0.1/api/job/`
- `ab -n 100 -c 50 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/`
- `ab -n 100 -c 50 -k  -s 60 -T application/json -p dataHigh.json  http://127.0.0.1/api/job/`

For more detailed information about the jobs processing, a visual dashboard is provided. It is powered by [Laravel Horizon](https://laravel.com/docs/7.x/horizon) which
can be accessed at:

http://127.0.0.1/horizon/dashboard

# API Documentacion
This project consists of 3 endpoints.
- **Base URL** : http://127.0.0.1/api
## **Enpoints**
--------------------------------
### GET /job/{id}
_Get the status of the specified job by id. It can be either pending or complete._ 

**Example**  
http://127.0.0.1/api/job/20

----------------------------------------
### POST /job
_Add a job to the specified queue (priority)._ 

The requiered payload for this endpoint must be presented as follow:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{
    "priority": "default",
    "submiter_id": 340
}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Where `priority` can be `"low","default"` or `"high"`. `submiter_id` is the id for the client who submit the job. It must be a postive integer.

**Example**  
http://127.0.0.1/api/job

--------------------------------------
### GET /job/average/runtime
_Get the average runtime of the all processed  jobs in the last five minutes._ 

**Example**  
http://127.0.0.1/api/job/average/runtime



----------------------------------------

