<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    function test_unauthenticated_user_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function test_an_authenticated_user_can_participate_in_forum_threads()
    {
        // given authenticated user
        $this->signIn();

        // and existing thread
        $thread = create('App\Thread');

        // when user addd reply to thread
        // simulates post and sending to server
        $reply = make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());

        // then their reply should be visible on page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function test_a_reply_requires_a_body()
    {
        // given signed in
        $this->withExceptionHandling()->signIn();

        // given we have a thread
        $thread = create('App\Thread');

        // given we have a reply wher body is null
        $reply = make('App\Reply', ['body' => null]);

        // when we post a reply without body we assert that
        // there are errors
        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
 }
