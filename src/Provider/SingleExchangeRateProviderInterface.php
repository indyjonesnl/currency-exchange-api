<?php

namespace App\Provider;

interface SingleExchangeRateProviderInterface
{
    public function getExchangeRate(string $pair): ?float;
}
