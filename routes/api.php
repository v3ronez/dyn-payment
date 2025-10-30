<?php

declare(strict_types=1);

use App\Http\Auth\AuthController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/* Route::get('', function (Request $request) { */
/*     return $request->user(); */
/* })->middleware('auth:sanctum'); */

Route::prefix('v1')->group(function () {
    Route::post('/users', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::prefix('v1')->group(function () {

    Route::get("/health-check", static function () {
        $connection = [
            "database" => 'offline',
            'redis' => 'offline',
        ];

        if (Redis::command('PING') == 'PONG') {
            $connection['redis'] = 'online';
        }
        $connection['database'] = DB::connection()->getPdo()->getAttribute(PDO::ATTR_CONNECTION_STATUS);

        return response()->json([
            'server running...' => true,
            'version' => 'v1',
            'services_status' => [
                'redis' => $connection['redis'],
                'database' => $connection['database'],
            ],
        ]);
    });
});
