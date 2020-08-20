<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use function GuzzleHttp\Promise\all;

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

    /** @test */
    function user_can_filter_threads_by_any_username()
    {
        //given signed in
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        //user creates thread
        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);

        // thread not created by another user
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    function userCanfilterThreadsByPopularity()
    {
        // given we have threads
        // with 4 replies, 3 replies, 2, 1, and 0 replies, respectively
        $threadWithFourReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithFourReplies->id], 4);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithOneReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithOneReplies->id], 1);

        $threadWithNoReplies = $this->thread;

        // when i filter all thread by popoularity
        $response = $this->getJson('threads?popular=1')->json();

        //the should be returned from most replies to least
        $this->assertEquals([4, 3, 2, 1, 0], array_column($response, 'replies_count'));
    }
}
