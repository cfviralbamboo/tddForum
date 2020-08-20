<?php

namespace Tests\Unit;

use App\Favorite;
use App\Reply;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guestsCanNotFavoriteAnything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    function authenticatedUserCanFavoriteAnyReply()
    {
        $this->signIn();

        // create reply --> which also creates a thread in the process
        $reply = create(Reply::class);

        // If I favortie a reply
        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function authenticatedUserMayOnlyFavoriteAReplyOnce()
    {
        $this->signIn();

        // create reply --> which also creates a thread in the process
        $reply = create(Reply::class);

        try {
            // If I favorite a reply more than once
        $this->post('replies/' . $reply->id . '/favorites');
        $this->post('replies/' . $reply->id . '/favorites');

        } catch (Exception $e) {
            $this->fail('Did not expect to insert same record twice');
        }


        $this->assertCount(1, $reply->favorites);
    }

}
