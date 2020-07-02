
# About this project

This is an implementation of a priority queue web-api server (REST API), that can be used to add “jobs” on a queue. Each job consists of a job id, submitter’s id, processor’s id (if its being processed) and a command to execute.

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).


# Installation

- Clone the repository
-  go to laradock folder
-  run docker-composer up -d nginx redis 
- wait for docker building process
- run docker-composer exec workspace bash
- run composer install 

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) 


### Testing
For testing, go to laradock folder, then run docker-composer exec workspace. Finally run php artisan test
#### Load Testing
For load testing it will be used the data which located in data folder. So those commands must be ran on that folder.

Test for Add Job n = 4.000 c = 1.000
- ab -n 4000 -c 1000 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/

Test for get Job status
- ab -n 4000 -c 1000 -k -s 60 http://127.0.0.1/api/job/2000/

For test priority queue, first add 50 jobs on low queue, then 50 on default and finally 50 on high.
- ab -n 100 -c 50 -k  -s 60 -T application/json -p dataLow.json  http://127.0.0.1/api/job/
- ab -n 100 -c 50 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/
- ab -n 100 -c 50 -k  -s 60 -T application/json -p dataHigh.json  http://127.0.0.1/api/job/

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

**Example**  
http://127.0.0.1/api/job

--------------------------------------
### GET /job/average/runtime
_Get the average runtime of the all jobs processed in the last five minutes._ 

**Example**  
http://127.0.0.1/api/job/average/runtime

Moreover, for more detailed information about the jobs processing, a visual dashboard is provided. It is powered by [Laravel Horizon](https://laravel.com/docs/7.x/horizon).
It can be accessed at:

http://127.0.0.1/horizon/dashboard

----------------------------------------

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
