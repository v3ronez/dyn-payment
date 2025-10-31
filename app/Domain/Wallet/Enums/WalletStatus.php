<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Enums;

use App\Enums\Traits\ToArray;

enum WalletStatus: int
{
    use ToArray;

    case Inactive = 0;
    case Active = 1;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
