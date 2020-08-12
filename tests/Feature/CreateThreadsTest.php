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
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');


    }


   /** @test */
   function test_an_authenticated_user_can_create_new_forum_threads()
   {
        //given we have a signed in user
        $this->signIn();

        // when we hit the endpoint to create a new thread
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        // then we visit the thread page
        // we should see the new thread
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
   }

   /** @test */
   function test_a_thread_requires_title()
   {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

   }

   /** @test */
   function test_a_thread_requires_body()
   {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

   }

   /** @test */
   function test_a_thread_requires_valid_channel()
   {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');

   }

   public function publishThread($overrides = [])
   {
       $this->withExceptionHandling()->signIn();

       $thread = make('App\Thread', $overrides);

       return $this->post('/threads', $thread->toArray());
   }
}
