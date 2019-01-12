<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    /**
     *
     * @test
     */

    use DatabaseMigrations;

    public function authenticatedUserCanParticipate()
    {
        //given we have authenticated user
        $user = factory('App\User')->create();
        $this->actingAs($user);

        //and an existing thread
        $thread = factory('App\Thread')->create();


        //when a user adds a reply to the thread
        $reply = factory('App\Reply')->create();

        
        $this->post($thread->path().'/replies', $reply->toArray());

        //then their reply should be included in the page

        $this->get($thread->path())->assertSee($reply->body);
    }

    public function testReplyRequiresBody()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make(['body' => null]);
        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertSessionHasErrors('body');
    }
}
