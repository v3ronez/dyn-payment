<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Actions;

use App\Contracts\Actions\Action;
use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Enums\WalletStatus;

class DeleteWallet implements Action
{
    private Wallet $success;

    private array $errors;

    public function __construct(private Wallet $wallet)
    {
        $this->success = new Wallet();
        $this->errors = [];
    }

    public function execute(): self
    {
        if ($this->wallet->balance > 0) {
            $this->errors[] = 'You cannot delete a wallet with balance';

            return $this;
        }
        if ($this->wallet->deleted_at !== null || $this->wallet->status === WalletStatus::Inactive) {
            $this->errors[] = 'You cannot delete a deleted wallet';

            return $this;
        }

        $deleted = tap($this->wallet->update([
            'status' => WalletStatus::Inactive,
            'deleted_at' => now(),
        ]));
        if (! $deleted) {
            $this->errors[] = 'Something went wrong while deleting wallet, please try again later';

            return $this;
        }

        $this->success = $this->wallet;

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
