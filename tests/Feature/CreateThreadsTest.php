<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guestsMayNotCreateThread()
    {
        $this->expectException(AuthenticationException::class);
        $thread = factory(Thread::class)->make();
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function authenticatedUserCanCreateThread()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $thread = factory(Thread::class)->make();
        $this->post('/threads', $thread->toArray());

        $this->get('/threads/'.$thread->id)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
