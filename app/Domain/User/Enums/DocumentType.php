<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

use App\Enums\Traits\ToArray;

enum DocumentType: string
{
    use ToArray;

    case PersonID = 'cpf';
    case LegalEntityID = 'cnpj';

    public function label(): string
    {
        return match ($this) {
            self::PersonID => 'cpf',
            self::LegalEntityID => 'cnpj',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
