<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $email,
        public readonly ?string $documentId,
        public readonly ?int $documentType,
        public readonly ?int $type,
        public readonly ?int $status,
        public readonly ?string $password,
        public readonly ?string $emailVerifiedAt,
        public readonly ?string $approvedAt,
    ) {
    }
}
