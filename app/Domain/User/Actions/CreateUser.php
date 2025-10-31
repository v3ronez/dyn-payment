<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\DTOs\UserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\UserType;
use App\Domain\User\ValueObject\Document\DocumentID;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateUser implements Action
{
    private mixed $success;

    private array $error;

    /** @var array<int,Dispatcher > */
    private array $event;

    public function __construct(
        private UserDTO $userDTO,
    ) {
        $this->success = null;
        $this->error = [];
        $this->event = [];

        $this->validateDocumentIdAndType();
    }

    public function execute(): self
    {
        if (count($this->error) > 0) {
            return $this;
        }

        try {
            DB::beginTransaction();
            $newUser = User::create([
                'first_name' => $this->userDTO->firstName,
                'last_name' => $this->userDTO->lastName,
                'email' => $this->userDTO->email,
                'document_id' => $this->userDTO->documentID,
                'document_type' => $this->userDTO->documentType,
                'type' => $this->userDTO->type,
                'status' => $this->userDTO->status,
                'password' => $this->userDTO->password,
                'email_verified_at' => $this->userDTO->emailVerifiedAt,
            ]);
            if (! $newUser) {
                DB::rollBack();
                $this->error[] = 'User not created';

                return $this;
            }

            $this->success = $newUser;
            DB::commit();

            return $this;
        } catch (UniqueConstraintViolationException) {
            DB::rollBack();
            $this->error[] = 'This user already exists';

            return $this;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->error[] = 'An inexpected error occurred while creating the user, please try again later';

            return $this;
        }
    }

    public function getSuccess(): mixed
    {
        return $this->success;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function hasError(): bool
    {
        return count($this->error) > 0;
    }

    private function validateDocumentIdAndType(): void
    {
        $document = DocumentID::validate($this->userDTO->documentID->value);
        if (strlen($document->value) === 11 && $this->userDTO->type != UserType::Individual) {
            $this->error[] = 'The document type is not valid for this user type';

            return;
        }
        if (strlen($document->value) === 14 && $this->userDTO->type != UserType::Juridical) {
            $this->error[] = 'The document type is not valid for this user type';

            return;
        }
    }
}
