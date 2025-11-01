<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\UserStatus;

class DeleteUser implements Action
{
    private bool $success;

    private array $errors;

    public function __construct(private User $user)
    {
        $this->success = false;
        $this->errors = [];
    }

    public function execute(): self
    {
        $updated = $this->user->update([
            'status' => UserStatus::Inactive,
            'deleted_at' => now(),
        ]);

        if (! $updated) {
            $this->errors[] = 'Something went wrong while deleting user, please try again later';

            return $this;
        }

        $this->success = true;

        return $this;
    }

    public function getSuccess(): bool
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
