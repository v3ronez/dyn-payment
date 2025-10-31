<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\ValueObjects\Document\DocumentID;

class UserDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly DocumentID $documentID,
        public readonly DocumentType $documentType,
        public readonly UserType $type,
        public readonly UserStatus $status,
        public readonly string $password,
        public readonly ?string $approvedAt,
        public readonly ?string $emailVerifiedAt,
    ) {
    }
}
