<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

use App\Enums\Traits\ToArray;

enum UserType: int
{
    use ToArray;

    case Individual = 0;
    case Juridical = 1;

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Pessoa Física',
            self::Juridical => 'Juridical',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
