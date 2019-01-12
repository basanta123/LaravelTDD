<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    /**
     *
     * @test
     */
    use DatabaseMigrations;

    public function testGuestMayNotCreateThreads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->get('/threads/create')->assertRedirect('/login');


        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray())->assertRedirect('/login');
    }


    public function testAuthenticatedUserCreateThreads()
    {
        //given we have signed in user
        $this->actingAs(factory('App\User')->create());



        //when we hit an endpoint to create a new thread
        $thread = factory('App\Thread')->make();

        $response = $this->post('/threads', $thread->toArray());

        //then we visit the thread page

        $this->get($response->headers->get('Location'))->assertSee($thread->title);
    }

    public function testThreadRequiresATitle()
    {
        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }

    public function testThreadRequiresABody()
    {
        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }

    public function testThreadRequiresValidChannel()
    {
        factory('App\Channel', 2)->create();
        
        
        $this->publishThread(['channel_id' => null])->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 299])->assertSessionHasErrors('channel_id');
    }
    public function publishThread($overrides)
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->make($overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
