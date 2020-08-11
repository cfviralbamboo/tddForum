<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function test_it_has_an_owner() {

        // create reply
        $reply = factory('App\Reply')->create();

        //
        $this->assertInstanceOf('App\User', $reply->owner);
    }
}
