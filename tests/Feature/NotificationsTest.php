<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, Auth::user()->notifications);

        $thread->addReply([
            'user_id'   => Auth::id(),
            'body'      => 'Some reply body',
        ]);

        $this->assertCount(0, Auth::user()->fresh()->notifications);



        $thread->addReply([
            'user_id'   => create(User::class)->id,
            'body'      => 'Some reply body',
        ]);

        $this->assertCount(1, Auth::user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        $user = Auth::user();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id'   => create(User::class)->id,
            'body'      => 'Some reply body',
        ]);

        $response = $this->getJson("/profiles/{$user->name}/notifications/")->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();
        $user = Auth::user();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id'   => create(User::class)->id,
            'body'      => 'Some reply body',
        ]);
        $this->assertCount(1, $user->unreadNotifications);


        $notificationId = $user->fresh()->unreadNotifications->first()->id;
        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
