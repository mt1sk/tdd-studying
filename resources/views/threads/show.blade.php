@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ $thread->title }}
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @auth()
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="{{ route('threads_add_reply', ['thread'=>$thread->id]) }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="body" id="body" cols="30" rows="5" class="form-control" placeholder="Body"></textarea>
                            <button type="submit" class="btn btn-default">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <p class="text-center">
                Please <a href="{{ route('login') }}">sign in</a> to participate.
            </p>
        @endauth
    </div>
@endsection
