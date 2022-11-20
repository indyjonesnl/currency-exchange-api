<?php

namespace App\Model;

use DateTime;
use JsonSerializable;

final class ExchangeRatesResponse implements JsonSerializable
{
    /** @var ExchangeRate[] */
    private array $rates = [];

    public function __construct(
        private readonly string $base,
    ) {}

    /** @return array<string,mixed> */
    public function jsonSerialize(): array
    {
        return [
            'base' => $this->getBase(),
            'date' => $this->getDate(),
            'rates' => $this->getRates(),
            'success' => true,
            'timestamp' => $this->getTimestamp(),
        ];
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getDate(): string
    {
        return (new DateTime())->format('Y-m-d');
    }

    public function addExchangeRate(ExchangeRate $exchangeRate): self
    {
        $this->rates[$exchangeRate->getSymbol()] = $exchangeRate;
        return $this;
    }

    /** @return float[] */
    public function getRates(): array
    {
        return \array_map(fn(ExchangeRate $exchangeRate) => $exchangeRate->getRate(), $this->rates);
    }

    public function getTimestamp(): int
    {
        return (new DateTime())->getTimestamp();
    }
}
