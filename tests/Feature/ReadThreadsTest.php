<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /**
     * @test
     */
    public function test_a_user_can_view_all_threads()
    {
        $response = $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function test_a_user_can_read_one_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function test_a_user_can_read_replies_associated_with_a_thread()
    {
        // creates reply associated with thread
        $reply = factory('App\Reply')
            ->create(['thread_id' => $this->thread->id]);

        // // when we visit the thread page we see the replies
        $this->get($this->thread->path())
            ->assertSee($reply->body);

    }

    /** @test */
    function test_a_user_can_filter_threads_according_to_a_channel()
    {
        // create channel
        $channel = create('App\Channel');

        // create thread in ^^above^^ channel
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);

        // create thread not in ^^above^^ channel
        //gets its own channel
        $threadNotInChannel = create('App\Thread');

        // checks the two threads and the channels they are in
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
}
