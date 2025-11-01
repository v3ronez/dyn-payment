<?php

declare(strict_types=1);

namespace App\Domain\Wallet\DTOs;

use App\Domain\Wallet\Enums\WalletStatus;

class UpdateWalletDTO
{
    public function __construct(
        public ?string $name,
        public ?int $balance,
        public ?WalletStatus $status,
    ) {
    }
}
