<?php


# number of jobs
$n = 400;
# Queue types
$queues = ['low','default','high'];


# Command?
$command = 'echo ';

$data_array = array();

$submiters = range(1,$n);
shuffle($submiters);

foreach ($submiters as $submiter_id) {
# code...

    # random queue
    $priority = array_rand(array_flip($queues), 1);
    # job submiters

    $data_array += ['priority' => $priority];
    $data_array += ['submiter_id' => $submiter_id];
    $data_array += ['$command' => $command];

}
print(sizeof($submiters));
$json = json_encode($data_array,JSON_PRETTY_PRINT);

$json_file = fopen('data.json', 'w');
fwrite($json_file, $json);   // here it will print the array pretty
fclose($json_file);


// Test for Add Job n = 4.000 c = 1.000
# ab -n 4000 -c 1000 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/
// Test for get Job status
# ab -n 4000 -c 1000 -k -s 60 http://127.0.0.1/api/job/2000/
#----------------------------------------------------------------------------------
# For test priority queue first add 50 jobs in low queue, then 50 in default and
# finally 50 in high.
# ab -n 100 -c 50 -k  -s 60 -T application/json -p dataLow.json  http://127.0.0.1/api/job/
# ab -n 100 -c 50 -k  -s 60 -T application/json -p dataDefault.json  http://127.0.0.1/api/job/
# ab -n 100 -c 50 -k  -s 60 -T application/json -p dataHigh.json  http://127.0.0.1/api/job/
