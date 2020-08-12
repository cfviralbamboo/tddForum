<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_channel_consists_of_threads()
    {
        //create channel
        $channel = create('App\Channel');

        // create thread in above channel
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        // check relationship: that thread is in channel
        $this->assertTrue($channel->threads->contains($thread));
    }
}
