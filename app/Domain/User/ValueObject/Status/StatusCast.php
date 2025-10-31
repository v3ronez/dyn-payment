<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject\Status;

use App\Domain\User\Enums\UserStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class StatusCast implements CastsAttributes
{
    /**
     * Transform the value from the database into the label.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $enum = UserStatus::tryFrom((int) $value);

        return $enum ? $enum->label() : null;
    }

    /**
     * Transform the label (or enum) into a value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof UserStatus) {
            return $value->value;
        }

        throw new InvalidArgumentException("Invalid UserStatus label or enum: {$value}");
    }
}
