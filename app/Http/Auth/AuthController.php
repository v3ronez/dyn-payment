<?php

declare(strict_types=1);

namespace App\Http\Auth;

use App\Domain\User\Actions\CreateUser;
use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\FormRequest\RegisterRequest;
use App\Domain\User\ValueObject\Document\DocumentID;
use App\Http\Controllers\Controller;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
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
                    $request->email_verified_at,
                )
            ))->execute();
            if ($action->hasError()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $action->getError(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $action->getSuccess(),
            ], 200);

        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'This user already exists.',
            ], Response::HTTP_CONFLICT);
        } catch (Throwable $e) {
            dd($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
