@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('user.profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </span>

                            @auth()
                            <form action="{{ $thread->path() }}" method="POST">
                                @csrf
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-link">Delete thread</button>
                            </form>
                            @endauth
                        </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}

                @auth()
                    <form method="POST" action="{{ route('threads_add_reply', ['thread'=>$thread->id, 'channel'=>$thread->channel->id]) }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="body" id="body" cols="30" rows="5" class="form-control" placeholder="Body"></textarea>
                            <button type="submit" class="btn btn-primary">Post</button>
                        </div>
                    </form>
                @else
                    <p class="text-center">
                        Please <a href="{{ route('login') }}">sign in</a> to participate.
                    </p>
                @endauth
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="#">{{ $thread->creator->name }}</a>, and currently
                        has {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
