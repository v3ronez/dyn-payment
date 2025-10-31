<?php

declare(strict_types=1);

namespace App\Domain\User\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as PaginationLengthAwarePaginator;

class GetAllUsers implements Action
{
    private LengthAwarePaginator $success;

    private array $errors;

    public function __construct(
        private int $page = 1,
        private int $perPage = 10,
    ) {
        $this->success = new PaginationLengthAwarePaginator([], 0, 15);
        $this->errors = [];
    }

    public function execute(): self
    {
        $users = User::query()
            ->with(['account.wallets'])
            ->orderBy('created_at', 'DESC')
            ->paginate(
                perPage: $this->perPage,
                page: $this->page,
            );
        if (! $users) {
            $this->errors[] = 'Users not found';

            return $this;
        }

        $this->success = $users;

        return $this;
    }

    public function getSuccess(): LengthAwarePaginator
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
