<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribceToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post($thread->path().'/subscriptions');

        $this->assertCount(1, $thread->subscriptions);

        /*$this->assertCount(0, Auth::user()->notifications);

        $thread->addReply([
            'user_id'   => Auth::id(),
            'body'      => 'Some reply body',
        ]);

        $this->assertCount(1, Auth::user()->fresh()->notifications);*/
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $thread->subscribe();

        $this->delete($thread->path().'/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
