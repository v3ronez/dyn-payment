<?php

declare(strict_types=1);

namespace App\Domain\Account\ValueObjects\Status;

use App\Domain\Account\Enums\AccountStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class StatusCast implements CastsAttributes
{
    /**
     * Transform the value from the database into the label.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $enum = AccountStatus::tryFrom((int) $value);

        return $enum->label() ?? null;
    }

    /**
     * Transform the label (or enum) into a value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof AccountStatus) {
            return $value->value;
        }

        throw new InvalidArgumentException("Invalid AccountStatus label or enum: {$value}");
    }
}
