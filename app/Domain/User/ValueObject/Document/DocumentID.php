<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject\Document;

use InvalidArgumentException;

class DocumentID
{
    public function __construct(public readonly string $document)
    {
    }

    public static function validate(string $document): self|InvalidArgumentException
    {
        $documentLength = strlen($document);

        return match (true) {
            $documentLength >= 11 && $documentLength < 20 => self::validateIndividual($document),
            $documentLength >= 14 && $documentLength < 25 => self::validateJuridical($document),
            default => throw new InvalidArgumentException('Document invalid!'),
        };
    }

    public static function validateIndividual(string $document)
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

            // Valida segundo dígito verificador
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

    public static function validateJuridical(string $document)
    {
        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $document)) {
            return false;
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

        return $document[12] == $digit1 && $document[13] == $digito2;
    }

    public function toString(): string
    {
        return $this->document;
    }

    public function toStringFormatted(): array|string|null
    {
        return match(true) {
            strlen($this->document) === 11 => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->document),
            strlen($this->document) === 14 => preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->document),
        };
    }
}
