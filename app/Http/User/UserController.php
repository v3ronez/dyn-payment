<?php

declare(strict_types=1);

namespace App\Http\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = auth()->user();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => $user,
            ],
            200
        );
    }

    public function show(Request $request)
    {
    }

    public function update(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }
}
