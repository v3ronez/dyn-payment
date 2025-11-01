<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\DTOs\UpdateUserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\ValueObjects\Document\DocumentID;

class UpdateUser implements Action
{
    private User $success;

    private array $error;

    public function __construct(
        private User $user,
        private UpdateUserDTO $userDTO,
    ) {
        $this->success = new User();
        $this->error = [];
    }

    public function execute(): self
    {
        $document = null;
        if ($this->userDTO->documentId) {
            $document = DocumentID::validate($this->userDTO->documentId);
        }

        tap($this->user->update([
            'first_name' => $this->userDTO->firstName ?? $this->user->first_name,
            'last_name' => $this->userDTO->lastName ?? $this->user->last_name,
            'email' => $this->userDTO->email ?? $this->user->email,
            'document_id' => $document ?? $this->user->document_id,
            'document_type' => $this->userDTO->documentType ?? DocumentType::from((string)$this->user->getRawOriginal('document_type')),
            'type' => $this->userDTO->type ?? UserType::from((int)$this->user->getRawOriginal('type')),
            'status' => $this->userDTO->status ?? UserStatus::from((int)$this->user->getRawOriginal('status')),
            'password' => $this->userDTO->password ?? $this->user->password,
            'email_verified_at' => $this->userDTO->emailVerifiedAt ?? $this->user->email_verified_at,
            'approved_at' => $this->userDTO->approvedAt ?? $this->user->approved_at,
        ]));

        $this->success = $this->user;

        return $this;
    }

    public function getSuccess(): User
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
}
