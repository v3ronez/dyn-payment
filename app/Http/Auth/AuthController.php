<?php

declare(strict_types=1);

namespace App\Http\Auth;

use App\Domain\Auth\Actions\Login;
use App\Domain\User\Actions\CreateUser;
use App\Domain\User\DTOs\UserDTO;
use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\FormRequests\RegisterRequest;
use App\Domain\User\ValueObjects\Document\DocumentID;
use App\Http\Controllers\Controller;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'The email is required.',
                'email.email' => 'The email is invalid.',
                'password.required' => 'The password is required.',
            ]);
            $action = (new Login(
                $request->email,
                $request->password,
            ))->execute();
            if ($action->hasError()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $action->getError(),
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => $action->getSuccess(),
            ], 200);

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

    public function register(RegisterRequest $request)
    {
        try {
            $action = (new CreateUser(
                new UserDTO(
                    $request->first_name,
                    $request->last_name,
                    $request->email,
                    new DocumentID($request->document_id),
                    DocumentType::from($request->document_type),
                    UserType::from($request->type),
                    UserStatus::Pending,
                    $request->password,
                    null,
                    $request->email_verified_at,
                ),
            ))->execute();
            if ($action->hasError()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $action->getError(),
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $action->getSuccess()->getFormattedAttributes(),
            ], Response::HTTP_CREATED);

        } catch (UniqueConstraintViolationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user already exists',
                'data' => null,
            ], Response::HTTP_CONFLICT);
        } catch (Throwable $e) {
            if (! app()->environment('production')) {
                dd($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            }

            //TODO: I definetly should send this error log to queue :D maybe another day
            Log::error(
                "[Error] It's not possible to create this user: {document}. error: {error}",
                [
                    'document' => substr($request->document_id, 0, 3).'***',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'An inexpected error occurred while creating the user, please try again later',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
