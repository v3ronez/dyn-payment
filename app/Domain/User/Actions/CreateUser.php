<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\DTOs\UserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\UserType;
use App\Domain\User\Jobs\ApproveNewUser;
use App\Domain\User\ValueObjects\Document\DocumentID;

class CreateUser implements Action
{
    private User $success;

    private array $error;

    public function __construct(
        private UserDTO $userDTO,
    ) {
        $this->success = new User();
        $this->error = [];

        $this->validateDocumentIdAndType();
    }

    public function execute(): self
    {
        if (count($this->error) > 0) {
            return $this;
        }
        /** @var User $newUser */
        $newUser = User::create([
            'first_name' => $this->userDTO->firstName,
            'last_name' => $this->userDTO->lastName,
            'email' => $this->userDTO->email,
            'document_id' => $this->userDTO->documentId,
            'document_type' => $this->userDTO->documentType,
            'type' => $this->userDTO->type,
            'status' => $this->userDTO->status,
            'password' => $this->userDTO->password,
            'email_verified_at' => $this->userDTO->emailVerifiedAt,
        ]);

        if (! $newUser) {
            $this->error[] = 'User not created';

            return $this;
        }

        dispatch(new ApproveNewUser($newUser))->onQueue('approve-new-users')->afterCommit();
        $newUser = new User($newUser->toArray());
        $this->success = $newUser;

        return $this;
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
        $document = DocumentID::validate($this->userDTO->documentId->value);
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
