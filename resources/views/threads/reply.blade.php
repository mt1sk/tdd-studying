<div class="card">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('user.profile', $reply->owner) }}">
                    {{ $reply->owner->name }}
                </a> said {{ $reply->created_at->diffForHumans() }}...
            </h5>

            <div>
                <form method="POST" action="{{ route('threads.favorite_reply', ['reply'=>$reply->id]) }}">
                    @csrf
                    <button class="btn btn-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count }} {{ Str::plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>
