<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects\Type;

use App\Domain\User\Enums\UserType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class TypeCast implements CastsAttributes
{
    /**
     * Transform the value from the database into the label.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $enum = UserType::tryFrom((int) $value);

        return $enum ? $enum->label() : null;
    }

    /**
     * Transform the label (or enum) into a value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof UserType) {
            return $value->value;
        }

        throw new InvalidArgumentException("Invalid UserType label or enum: {$value}");
    }
}
