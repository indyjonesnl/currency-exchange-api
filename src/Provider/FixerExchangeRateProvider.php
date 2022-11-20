<?php

namespace App\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FixerExchangeRateProvider implements ExchangeRateProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $fixerClient,
        private readonly string $fixerIoApiKey,
    ) {}

    public function getExchangeRates(string $baseCurrency, string $symbols): array
    {
        $exchangeRates = [];

        $response = $this->fixerClient->request(
            Request::METHOD_GET,
            '/fixer/latest',
            [
                'headers' => ['apikey' => $this->fixerIoApiKey],
                'query' => ['base' => $baseCurrency, 'symbols' => $symbols],
            ],
        );

        $apiRates = \json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        if (\is_array($apiRates)) {
            $symbols = \explode(',', $symbols);
            foreach ($symbols as $symbol) {
                $exchangeRates[$symbol] = $apiRates['rates'][$symbol];
            }
        }

        return $exchangeRates;
    }
}
