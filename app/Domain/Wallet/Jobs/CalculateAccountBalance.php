<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Jobs;

use App\Domain\Account\Entity\Account;
use App\Domain\User\Entity\User;
use App\Domain\Wallet\Entity\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CalculateAccountBalance implements ShouldQueue
{
    use Queueable;

    public function __construct(private User $user, private Wallet $wallet)
    {
    }

    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $currentBalance = Account::query()
                ->where('id', $this->wallet->account_id)
                ->lockForUpdate()
                ->first()->balance;
            $newBalance = $currentBalance + $this->wallet->balance;
            Account::query()
                ->where('id', $this->wallet->account_id)
                ->update([
                    'balance' => $newBalance,
                ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error(
                "[Error] It's not possible to calculate account balance: {document}. error: {error}",
                [
                    'document' => substr($this->user->document_id->toString(), 0, 3).'***',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }
    }
}
