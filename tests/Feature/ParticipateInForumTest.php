<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticatedUserMayNotAddReplies()
    {
        $this->expectException(AuthenticationException::class);
        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->create();
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());
    }

    /** @test */
    public function authenticatedUserMayParticipateInForumThreads()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

        $this->get('/threads/'.$thread->id)
            ->assertSee($reply->body);
    }
}
