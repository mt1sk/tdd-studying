<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function userCanViewAllThreadsTest()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function userCanViewSingleThread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function userCanReadRepliesThatAreAssociatedWithThread()
    {
        $reply = factory(Reply::class)
            ->create(['thread_id'=>$this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function userCanFilterThreadsAccordingToChannel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id'=>$channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
}
