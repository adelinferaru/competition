<?php
return [
    'leaderboard_storage' => 'redis',

    'keys' => [
        \App\Models\Competition::class => 'competition:{id}',
        \App\Models\Player::class => 'player:{id}',
        'leaderboard' => 'competition:{id}:leaderboard'
    ],

    'pagination' => [
        'per_page' => 10
    ],
];
