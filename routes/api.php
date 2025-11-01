<?php

declare(strict_types=1);

use App\Http\Auth\AuthController;
use App\Http\User\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/users', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->prefix('users')->group(function () {
        Route::get('/me', [UserController::class, 'me'])->name('users.me');
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

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
