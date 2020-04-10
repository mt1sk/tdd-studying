<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['channel'];

    protected $appends = ['isSubscribedTo'];

    /**
     * for all queries
     * to disable global scope Model::withoutGlobalScopes(['scopeName'])->first();
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('creator', function ($builder) {
            $builder->with('creator');
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: Auth::id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: Auth::id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', Auth::id())
            ->exists();
    }

    /**
     * @param $reply
     */
    public function notifySubscribers($reply): void
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }
}
