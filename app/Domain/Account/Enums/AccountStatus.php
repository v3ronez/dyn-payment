<?php

declare(strict_types=1);

namespace App\Domain\Account\Enums;

use App\Enums\Traits\ToArray;

enum AccountStatus: int
{
    use ToArray;

    case Inactive = 0;
    case Active = 1;
    case Blocked = 2;

    public function label(): string
    {
        return match ($this) {
            self::Inactive => 'Inactive',
            self::Active => 'Active',
            self::Blocked => 'Blocked',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
