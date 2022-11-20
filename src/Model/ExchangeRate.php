<?php

namespace App\Model;

use DateTimeImmutable;
use JsonSerializable;

final class ExchangeRate implements JsonSerializable
{
    private DateTimeImmutable $cacheDate;

    public function __construct(
        private readonly string $base,
        private readonly string $symbol,
        private readonly float $rate,
    ) {
        $this->cacheDate = new DateTimeImmutable();
    }

    /** @return array<string,mixed> */
    public function jsonSerialize(): array
    {
        return [
            'base' => $this->base,
            'cacheDate' => $this->getCacheDate(),
            'rate' => $this->getRate(),
            'symbol' => $this->getSymbol(),
        ];
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCacheDate(): DateTimeImmutable
    {
        return $this->cacheDate;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
