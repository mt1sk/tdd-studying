<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate();
    }

    public function store($channelId, Thread $thread)
    {
        $this->validate(\request(), ['body' => 'required']);

        $reply = $thread->addReply([
            'body' => \request('body'),
            'user_id' => auth()->id(),
        ]);

        if (\request()->expectsJson()) {
            $reply->load('owner');
            return response()->json($reply, 201);
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $reply->update(['body' => $request->get('body')]);
    }

    public function destroy(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if ($request->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }
}
