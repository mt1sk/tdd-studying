<?php

namespace Tests\Feature;

use App\Reply;
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
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticatedUserCanCreateThread()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function threadRequiresTitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function threadRequiresBody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function threadRequiresValidChannel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();
        $this->assertGuest();

        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect('/login');
    }

    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {
        // TODO:
    }

    /** @test */
    public function thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->deleteJson($thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
