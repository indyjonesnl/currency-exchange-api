<?php

namespace App\Command;

use App\Model\ExchangeRate;
use App\Model\Symbols;
use App\Provider\FixerExchangeRateProvider;
use DateInterval;
use DateTime;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand('app:load-rates', 'Loads exchange rates from Fixer IO and stores them in a local cache.')]
final class LoadLatestExchangeRatesCommand extends Command
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly FixerExchangeRateProvider $fixerExchangeRateProvider,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            'base',
            'b',
            InputOption::VALUE_OPTIONAL,
            'The base currency',
            Symbols::EUR,
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $baseCurrency = $input->getOption('base');
        if ($this->determineIfCacheNeedsWarmup($baseCurrency)) {
            $this->loadExchangeRates($baseCurrency);
        }

        return self::SUCCESS;
    }

    private function loadExchangeRates(string $baseCurrency): void
    {
        // Set the expiration to 7 days, so the data is kept even when an external service is not available (today).
        $expiration = new DateInterval('P7D');

        $exchangeRatesResponse = $this->fixerExchangeRateProvider->getExchangeRates(
            $baseCurrency,
            \implode(',', Symbols::SYMBOL_KEYS),
        );
        // Filter array? to remove symbols without exchange rate.
        foreach ($exchangeRatesResponse as $symbol => $rate) {
            $cacheItem = $this->cache->getItem($baseCurrency . '-' . $symbol);

            $cacheItem->set(new ExchangeRate($baseCurrency, $symbol, $rate));
            $cacheItem->expiresAfter($expiration);
            $this->cache->saveDeferred($cacheItem);
        }

        $this->cache->commit();
    }

    private function determineIfCacheNeedsWarmup(string $baseCurrency): bool
    {
        $yesterday = (new DateTime())->sub(DateInterval::createFromDateString('23 hours'));

        foreach(Symbols::SYMBOL_KEYS as $symbol) {
            /** @var ExchangeRate|null $cacheItem */
            $cacheItem = $this->cache->get(
                $baseCurrency . '-' . $symbol,
                function(CacheItem $cacheItem): ?ExchangeRate {
                    if (!$cacheItem->isHit()) {
                        return null;
                    }

                    return $cacheItem->get();
                }
            );

            if ($cacheItem === null) {
                return true;
            }

            if ($cacheItem->getCacheDate() < $yesterday) {
                return true;
            }
        }

        return false;
    }
}
