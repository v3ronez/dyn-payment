<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

use App\Enums\Traits\ToArray;

enum UserStatus: string
{
    use ToArray;

    case Inactive = 0;
    case Active = 1;
    case Pending = 2;

    public function label(): string
    {
        return match ($this) {
            self::Inactive => 'Inactive',
            self::Active => 'Active',
            self::Pending => 'Pending',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
