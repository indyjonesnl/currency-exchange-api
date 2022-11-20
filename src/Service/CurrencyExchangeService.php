<?php

namespace App\Service;

use App\Model\ExchangeRate;
use App\Model\ExchangeRatesResponse;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

final class CurrencyExchangeService implements CurrencyExchangeServiceInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {}

    public function getExchangeRates(string $base, string $symbols): ExchangeRatesResponse
    {
        $exchangeRatesResponse = new ExchangeRatesResponse($base);

        $symbols = \explode(',', $symbols);
        foreach ($symbols as $symbol) {
            if ($base === $symbol) {
                continue;
            }

            $key = $base . '-' . $symbol;

            /** @var ExchangeRate $exchangeRate */
            $exchangeRate = $this->cache->get(
                $key,
                function (CacheItem $cacheItem): ?ExchangeRate {
                    if ($cacheItem->isHit()) {
                        $value = $cacheItem->get();
                        if ($value instanceof ExchangeRate) {
                            return $value;
                        }
                    }

                    return null;
                }
            );

            if ($exchangeRate !== null) {
                $exchangeRatesResponse->addExchangeRate($exchangeRate);
            }
        }

        return $exchangeRatesResponse;
    }
}
