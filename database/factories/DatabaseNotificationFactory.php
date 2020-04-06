<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \Illuminate\Notifications\DatabaseNotification;
use Faker\Generator as Faker;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id'                => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type'              => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id'     => auth()->id() ?: factory(\App\User::class),
        'notifiable_type'   => 'App\User',
        'data'              => ['foo' => 'bar'],
    ];
});
