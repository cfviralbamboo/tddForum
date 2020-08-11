<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    function test_guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }

    function test_guests_cannot_see_create_threads_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

   /** @test */
   function test_an_authenticated_user_can_create_new_forum_threads()
   {
        //given we have a signed in user
        $this->signIn();

        // when we hit the endpoint to create a new thread
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        // then we visit the thread page
        $this->get($thread->path());

        // we should see the new thread
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
   }
}
