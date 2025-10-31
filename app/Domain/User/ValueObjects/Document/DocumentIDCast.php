<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects\Document;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class DocumentIDCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return DocumentID::validate($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        throw_if(
            ! $value instanceof DocumentID,
            new InvalidArgumentException("The given value is not an Document")
        );

        return $value->toString();
    }
}
