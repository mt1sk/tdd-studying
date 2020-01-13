<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    public function testGuestsCanNotFavoriteAnything()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }

    public function testAuthenticatedUserCanFavoriteAnyReply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');
        $reply->favorite();

        $this->delete('/replies/'.$reply->id.'/favorites');
        $this->assertCount(0, $reply->favorites);
    }

    public function testAuthenticatedUserMayFavoriteReplyOnce()
    {
        $this->signIn();
        $reply = create('App\Reply');

        try {
            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        } catch (\Exception $exception) {
            $this->fail('Did not expect to insert the same record set twice');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
