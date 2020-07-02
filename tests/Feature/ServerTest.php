<?php

namespace Tests\Feature;


use Tests\TestCase;

class ServerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    private $priority = 'default';
    private $submiter_id = 5;

    // Test if the server is running
    public function testServerStatus()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // function for make post request
    public function postJob(){
        $response = $this->postJson('/api/job',
                    ['priority' => $this->priority,
                    'submiter_id' => $this->submiter_id,
                    ]);
        return $response;
    }

    // Test if it is able to add a job
    public function testAddJob()
    {
        $response = $this->postJob();
        $response
                ->assertStatus(201)
                ->assertJson([
                'created' => true,
                ]);
    }

    // Test if it is able to get the
    // status for an especific job
    public function testGetJobStatus()
    {
        $content = $this->postJob()->decodeResponseJson();
        $id = $content['id'];
        $response = $this->get('/api/job/'.$id);
        $response->assertStatus(200)
                 ->assertJson([
                    'Job status' => 'pending',
                    ]);
    }
    // Test get average runtime
    public function testGetAverageRuntime()
    {

        $response = $this->get('/api/job/average/runtime');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'throughput',
                     'runtime',
                     'time'
                 ]);
    }

}
