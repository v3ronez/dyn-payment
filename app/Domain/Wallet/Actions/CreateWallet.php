<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;
use App\Domain\Wallet\DTOs\WalletDTO;
use App\Domain\Wallet\Entity\Wallet;
use App\Domain\Wallet\Jobs\CalculateAccountBalance;

class CreateWallet implements Action
{
    private Wallet $success;

    private array $errors;

    public function __construct(private User $user, private WalletDTO $wallet)
    {
        $this->success = new Wallet();
        $this->errors = [];
    }

    public function execute(): self
    {
        $newWallet = Wallet::create([
            'name' => $this->wallet->name,
            'balance' => $this->wallet->balance ?? 0,
            'account_id' => $this->user->account_id,
            'status' => $this->wallet->status,
            'type' => $this->wallet->type,
        ]);

        if (! $newWallet) {
            $this->errors[] = 'Something went wrong while creating wallet, please try again later';

            return $this;
        }

        dispatch(new CalculateAccountBalance($this->user, $newWallet))->onQueue('calculate-account-balance');
        $this->success = $newWallet;

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
