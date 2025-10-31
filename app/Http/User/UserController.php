<?php

declare(strict_types=1);

namespace App\Http\User;

use App\Domain\User\Actions\GetUserById;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $action = (new GetUserById(auth()->user()->id))->execute();
        if ($action->hasError()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => $action->getSuccess(),
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
