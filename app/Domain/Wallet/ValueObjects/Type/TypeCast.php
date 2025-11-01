<?php

declare(strict_types=1);

namespace App\Domain\Wallet\ValueObjects\Type;

use App\Domain\Wallet\Enums\WalletType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class TypeCast implements CastsAttributes
{
    /**
     * Transform the value from the database into the label.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return WalletType::from((int) $value)->label();
    }

    /**
     * Transform the label (or enum) into a value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof WalletType) {
            return $value->value;
        }

        throw new InvalidArgumentException("Invalid WalletType label or enum: {$value}");
    }
}
