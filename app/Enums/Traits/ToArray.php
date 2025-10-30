<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait ToArray
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
