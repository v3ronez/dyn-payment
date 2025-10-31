<?php

declare(strict_types=1);

namespace App\Domain\User\Jobs;

use App\Domain\Account\Enums\AccountStatus;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\UserStatus;
use App\Domain\Wallet\Enums\WalletStatus;
use App\Domain\Wallet\Enums\WalletType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ApproveNewUser implements ShouldQueue
{
    use Queueable;

    public function __construct(private User $user)
    {
    }

    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $accountId = $this->createAccount();
            $this->createWallet($accountId);

            $this->user->update([
                'status' => UserStatus::Active,
                'approved_at' => now(),
                'account_id' => $accountId,
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error(
                "[Error] It's not possible to approve this user: {document}. error: {error}",
                [
                    'document' => substr($this->user->document_id->toString(), 0, 3).'***',
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }
    }

    private function createAccount(): string
    {
        $accountId = (string) Str::uuid7();
        DB::table('accounts')->insert([
            'id' => $accountId,
            'user_id' => $this->user->id,
            'number' => (string) Str::ulid(),
            'balance' => 0,
            'status' => AccountStatus::Active->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $accountId;
    }

    public function createWallet(string $accountId): string
    {
        $walletId = (string) Str::uuid7();
        DB::table('wallets')->insert([
            'id' => $walletId,
            'name' => 'Default',
            'balance' => 0,
            'account_id' => $accountId,
            'type' => WalletType::Default->value,
            'status' => WalletStatus::Active->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return  $walletId;
    }
}
