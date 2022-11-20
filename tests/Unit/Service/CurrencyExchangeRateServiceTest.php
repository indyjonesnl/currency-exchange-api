<?php

namespace App\Tests\Unit\Service;

use App\Model\ExchangeRate;
use App\Model\ExchangeRatesResponse;
use App\Model\Symbols;
use App\Service\CurrencyExchangeService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

final class CurrencyExchangeRateServiceTest extends TestCase
{
    public function testService(): void
    {
        $bamRate = new ExchangeRate(Symbols::EUR, 'BAM', 1.95);
        $hrkRate = new ExchangeRate(Symbols::EUR, 'HRK', 7.51);

        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('get')->willReturnCallback(function(string $key) use ($bamRate, $hrkRate) {
            return match($key)  {
                'EUR-BAM' => $bamRate,
                'EUR-HRK' => $hrkRate,
                default => throw new \Exception(),
            };
        });

        $service = new CurrencyExchangeService($cacheMock);
        $result = $service->getExchangeRates(Symbols::EUR, 'BAM,HRK');

        $expectation = (new ExchangeRatesResponse(Symbols::EUR))
            ->addExchangeRate($bamRate)
            ->addExchangeRate($hrkRate);

        self::assertEquals($expectation, $result);
    }
}
