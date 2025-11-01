<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects\Document;

use InvalidArgumentException;

class DocumentID
{
    public function __construct(public readonly string $value)
    {
    }

    public static function validate(string $document): self|InvalidArgumentException
    {
        $document = preg_replace('/\D/', '', $document);
        $documentLength = strlen($document);

        return match (true) {
            $documentLength === 11 => self::validateIndividual($document),
            $documentLength === 14 => self::validateJuridical($document),
            default => throw new InvalidArgumentException('Document invalid!'),
        };
    }

    public static function validateIndividual(string $document): self|InvalidArgumentException
    {
        $document = preg_replace('/\D/', '', $document);
        if (strlen($document) === 14) {

            throw_if(preg_match('/^(\d)\1{14}$/', $document), new InvalidArgumentException('Document invalid!'));
            for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
                $soma += $document[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }

            $resto = $soma % 11;

            if ($document[12] != ($resto < 2 ? 0 : 11 - $resto)) {
                throw new InvalidArgumentException('Document invalid!');
            }

            for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
                $soma += $document[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }
            $resto = $soma % 11;

            if ($document[13] != ($resto < 2 ? 0 : 11 - $resto)) {
                throw new InvalidArgumentException('Document invalid!');
            }

            return new self($document);

        }

        throw_if(strlen($document) < 11, new InvalidArgumentException('Document invalid!'));
        throw_if(preg_match('/^(\d)\1{10}$/', $document), new InvalidArgumentException('Document invalid!'));

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $document[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($document[$c] != $d) {
                throw new InvalidArgumentException('Document invalid!');
            }
        }

        return new self($document);
    }

    public static function validateJuridical(string $document): self|InvalidArgumentException
    {
        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) != 14) {
            return new InvalidArgumentException('Document invalid!');
        }

        if (preg_match('/(\d)\1{13}/', $document)) {
            return new InvalidArgumentException('Document invalid!');
        }

        $weight1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $plus1 = 0;
        for ($i = 0; $i < 12; $i++) {
            $plus1 += $document[$i] * $weight1[$i];
        }
        $rest1 = $plus1 % 11;
        $digit1 = ($rest1 < 2) ? 0 : 11 - $rest1;

        $weight2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma2 = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma2 += $document[$i] * $weight2[$i];
        }
        $resto2 = $soma2 % 11;
        $digito2 = ($resto2 < 2) ? 0 : 11 - $resto2;

        $valid = $document[12] == $digit1 && $document[13] == $digito2;
        if (! $valid) {
            throw new InvalidArgumentException('Document invalid!');
        }

        return new self($document);
    }

    public function toString(): string
    {
        return preg_replace('/\D/', '', $this->value);
    }

    public function toStringFormatted(): array|string|null
    {
        $document = preg_replace('/\D/', '', $this->value);

        return match(true) {
            strlen($document) === 11 => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $document),
            strlen($document) === 14 => preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $document),
            default => throw new InvalidArgumentException('Impossible to format this document'),
        };
    }
}
