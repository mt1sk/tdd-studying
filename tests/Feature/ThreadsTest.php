<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function userCanViewAllThreadsTest()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads');
        $response->assertSee($thread->title);
    }

    /** @test */
    public function userCanViewSingleThread()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads/' . $thread->id);
        $response->assertSee($thread->title);
    }
}
