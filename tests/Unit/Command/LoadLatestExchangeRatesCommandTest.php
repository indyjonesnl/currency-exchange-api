<?php

namespace App\Tests\Unit\Command;

use App\Command\LoadLatestExchangeRatesCommand;
use App\Model\ExchangeRate;
use App\Model\Symbols;
use App\Provider\FixerExchangeRateProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class LoadLatestExchangeRatesCommandTest extends TestCase
{
    public function testNoHttpCallIsMadeWhenCacheKeysExist(): void
    {
        $inputMock = $this->createMock(InputInterface::class);
        $inputMock->method('getOption')->with('base')->willReturn(Symbols::EUR);
        $outputMock = $this->createMock(OutputInterface::class);
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $cacheMock = $this->createMock(CacheInterface::class);

        $cacheMock->method('get')->willReturnCallback(function() {
            return new ExchangeRate(Symbols::EUR, 'USD', 1.0);
        });

        $fixerProvider = new FixerExchangeRateProvider(
            $httpClientMock,
            'api-key'
        );

        $httpClientMock->expects(self::never())->method('request');

        $command = new LoadLatestExchangeRatesCommand($cacheMock, $fixerProvider);
        $command->run($inputMock, $outputMock);
    }
}
