<?php

declare(strict_types=1);

namespace App\Domain\User\Validations\Rules;

use App\Domain\User\ValueObjects\Document\DocumentID;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class DocumentIDRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            DocumentID::validate($value);
        } catch (InvalidArgumentException $e) {
            $fail($e->getMessage());
        }
    }

    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
