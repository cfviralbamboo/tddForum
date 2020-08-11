<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    function test_unauthenticated_user_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->create();

        $this->post('/threads/1/replies', []);
    }

    /** @test */
    function test_an_authenticated_user_can_participate_in_forum_threads()
    {
        // given authenticated user
        $this->be($user = factory('App\User')->create());

        // and existing thread
        $thread = factory('App\Thread')->create();

        // when user addd reply to thread
        // simulates post and sending to server
        $reply = factory('App\Reply')->make();
        $this->post($thread->path().'/replies', $reply->toArray());

        // then their reply should be visible on page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
 }
