<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("v1/health-check", static function () {
    $connection = [
        "database" => 'offline',
        'redis' => 'offline',
    ];

    if (Redis::command('PING') == 'PONG') {
        $connection['redis'] = 'online';
    }
    $connection['database'] = DB::connection()->getPdo()->getAttribute(PDO::ATTR_CONNECTION_STATUS);

    return response()->json([
        'server running' => true,
        'services_status' => [
            'redis' => $connection['redis'],
            'database' => $connection['database'],
        ],
    ]);
});
