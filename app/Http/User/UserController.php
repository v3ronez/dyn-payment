<?php

declare(strict_types=1);

namespace App\Http\User;

use App\Domain\User\Actions\GetAllUsers;
use App\Domain\User\Actions\GetUserById;
use App\Domain\User\Actions\UpdateUser;
use App\Domain\User\DTOs\UpdateUserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\FormRequests\UpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

    public function update(UpdateRequest $request, User $user)
    {
        try {
            $action = (new UpdateUser(
                $user,
                new UpdateUserDTO(
                    $request->first_name,
                    $request->last_name,
                    $request->email,
                    $request->document_id,
                    $request->document_type,
                    $request->type,
                    $request->status,
                    $request->password,
                    $request->email_verified_at,
                    $request->approved_at,
                ),
            ))->execute();
            if ($action->hasError()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $action->getSuccess()->getFormattedAttributes(),
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            if (! app()->environment('production')) {
                dd($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            }

            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
    }
}
