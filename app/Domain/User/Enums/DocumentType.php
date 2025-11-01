<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

use App\Enums\Traits\ToArray;

enum DocumentType: string
{
    use ToArray;

    case IndividualID = 'cpf';
    case LegalEntityID = 'cnpj';

    public function label(): string
    {
        return match ($this) {
            self::IndividualID => 'cpf',
            self::LegalEntityID => 'cnpj',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
