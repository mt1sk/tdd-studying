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
        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guestsMayNotSeeCreatePage()
    {
        /*$this->expectException(AuthenticationException::class);*/
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticatedUserCanCreateThread()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $this->post('/threads', $thread->toArray());

        $this->get('/threads/'.$thread->id)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
