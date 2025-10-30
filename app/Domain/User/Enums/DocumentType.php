<?php

declare(strict_types=1);

namespace App\Domain\User\Enums;

use App\Enums\Traits\ToArray;

enum DocumentType: string
{
    use ToArray;

    case IndivudualID = 'cpf';
    case LegalEntityID = 'cnpj';

    public function label(): string
    {
        return match ($this) {
            self::IndivudualID => 'cpf',
            self::LegalEntityID => 'cnpj',
            default => throw new \Exception('Unknown enum value requested for the label'),
        };
    }
}
