<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;
use App\Domain\Wallet\DTOs\UpdateWalletDTO;
use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Jobs\CalculateAccountBalance;

class UpdateWallet implements Action
{
    private Wallet $success;

    private array $errors;

    public function __construct(
        private User $user,
        private Wallet $wallet,
        private UpdateWalletDTO $dto,
    ) {
        $this->success = new Wallet();
        $this->errors = [];
    }

    public function execute(): self
    {
        if (! $this->wallet->account->user->is($this->user)) {
            $this->errors[] = 'You are not allowed to update this wallet';

            return $this;
        }

        tap($this->wallet->update([
            'name' => $this->dto->name ?? $this->wallet->name,
            'balance' => $this->dto->balance ?? $this->wallet->balance,
            'status' => $this->dto->status ?? $this->wallet->status,
        ]));

        $this->success = $this->wallet;

        dispatch(new CalculateAccountBalance($this->user, $this->wallet))->onQueue('calculate-account-balance');

        return $this;
    }

    public function getSuccess(): Wallet
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
