<?php

namespace App\Service;

use App\Model\ExchangeRatesResponse;

interface CurrencyExchangeServiceInterface
{
    public function getExchangeRates(string $base, string $symbols): ExchangeRatesResponse;
}
