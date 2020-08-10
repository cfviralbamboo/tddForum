<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    // use RefreshDatabase;
    // use DatabaseMigrations;

    /**
     * @test
     */
    public function test_a_user_can_browse_threads()
    {
        $thread = factory('App\Thread')->create();

        $response = $this->get('/threads');
        $response->assertSee($thread->title);

        $response = $this->get('/threads/' . $thread->id);
        $response->assertSee($thread->title);

    }
}
