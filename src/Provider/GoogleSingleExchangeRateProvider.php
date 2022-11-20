<?php

namespace App\Provider;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GoogleSingleExchangeRateProvider implements SingleExchangeRateProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $googleClient,
    ) {}

    public function getExchangeRate(string $pair): ?float
    {
        [$toCurrency, $fromCurrency] = \explode('-', $pair);
        $response = $this->googleClient->request(
            Request::METHOD_GET,
            '/search',
            ['query' => ['q' => '1 ' . $fromCurrency . ' to ' . $toCurrency]],
        );

        $crawler = new Crawler($response->getContent());
        return \floatval(
            \str_replace(
                ',',
                '.',
                \str_replace(
                    '.',
                    '',
                    $crawler->filterXPath('//div[@id="knowledge-currency__updatable-data-column"]/div[1]/div[2]/span[1]')->text(),
                ),
            )
        );
    }
}
