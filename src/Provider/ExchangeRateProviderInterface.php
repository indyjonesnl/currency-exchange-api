<?php

namespace App\Provider;

interface ExchangeRateProviderInterface
{
    /** @return array<string, string> Key = currency pair, value = exchange rate string */
    public function getExchangeRates(string $baseCurrency, string $symbols): array;
}
