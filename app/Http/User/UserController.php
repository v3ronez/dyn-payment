<?php

declare(strict_types=1);

namespace App\Http\User;

use App\Domain\User\Actions\GetAllUsers;
use App\Domain\User\Actions\GetUserById;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $action = (new GetUserById(auth()->user()->id))->execute();
        if ($action->hasError()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $action->getSuccess(),
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {
        $action = (new GetAllUsers())->execute();
        if ($action->hasError()) {
            return response()->json([
                'status' => 'error',
                'message' => $action->getError()[0]
                    ??
                    'Error on fetching users. Please try again later',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $action->getSuccess(),
        ], Response::HTTP_OK);
    }

    public function show(Request $request, string $userId)
    {
        $action = (new GetUserById(auth()->user()->id))->execute();
        if ($action->hasError()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $action->getSuccess(),
        ], Response::HTTP_OK);

    }

    public function update(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }
}
