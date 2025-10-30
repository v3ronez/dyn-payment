<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

interface Action
{
    public function execute(): self;

    public function getSuccess(): mixed;

    public function getError(): array;

    public function hasError(): bool;
}
