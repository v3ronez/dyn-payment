<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;

class GetUserById implements Action
{
    private User $success;

    private array $errors;

    public function __construct(
        private string $userId,
    ) {
        $this->success = new User();
        $this->errors = [];
    }

    public function execute(): self
    {
        $user = User::with(['account.wallets'])->find($this->userId);
        if (! $user) {
            $this->errors[] = 'User not found';

            return $this;
        }

        $this->success = $user;

        return $this;
    }

    public function getSuccess(): User
    {
        return $this->success;
    }

    public function getError(): array
    {
        return $this->errors;
    }

    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }
}
