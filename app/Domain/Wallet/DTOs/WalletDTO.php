<?php

declare(strict_types=1);

namespace App\Domain\Wallet\DTOs;

use App\Domain\Wallet\Enums\WalletStatus;
use App\Domain\Wallet\Enums\WalletType;

class WalletDTO
{
    public function __construct(
        public string $name,
        public int $balance,
        public WalletStatus $status,
        public WalletType $type,
    ) {
    }
}
