<?php

declare(strict_types=1);

namespace App\Domain\Wallet\ValueObjects\Status;

use App\Domain\Wallet\Enums\WalletStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class StatusCast implements CastsAttributes
{
    /**
     * Transform the value from the database into the label.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return WalletStatus::from((int) $value);
    }

    /**
     * Transform the label (or enum) into a value for storage.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof WalletStatus) {
            return $value->value;
        }

        throw new InvalidArgumentException("Invalid WalletStatus label or enum: {$value}");
    }
}
