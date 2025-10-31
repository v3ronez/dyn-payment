<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Enums;

use App\Enums\Traits\ToArray;

enum WalletType: int
{
    use ToArray;

    case Default = 0;
    case Wallet = 1;

    public function label(): string
    {
        return match ($this) {
            self::Default => 'Default',
            self::Wallet => 'Wallet',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
