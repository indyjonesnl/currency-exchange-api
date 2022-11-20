<?php

namespace App\Tests\Unit\Provider;

use App\Provider\GoogleSingleExchangeRateProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class GoogleExchangeRateProviderTest extends TestCase
{
    public function testProvider(): void
    {
        $mockResponse = new MockResponse(\file_get_contents(__DIR__ . '/../../resources/google_search_result.html'));
        $httpClient = new MockHttpClient([$mockResponse]);

        $provider = new GoogleSingleExchangeRateProvider($httpClient);

        self::assertSame(0.21, $provider->getExchangeRate('EUR-PLN'));
    }
}
