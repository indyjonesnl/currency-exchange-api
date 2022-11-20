<?php

namespace App\Command;

use App\Model\Symbols;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:store:fixer', 'Saves the JSON response from the Fixer API in local files for debugging purposes.')]
final class StoreFixerResponseCommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface $fixerClient,
        private readonly string $fixerIoApiKey,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->fixerClient->request(Request::METHOD_GET, '/fixer/latest', [
            'headers' => ['apikey' => $this->fixerIoApiKey],
            'query' => [
                'base' => Symbols::EUR,
                'symbols' => Symbols::SYMBOLS_COMBINED,
            ],
        ]);

        $latestJson = $response->getContent();

        \file_put_contents(__DIR__ . '/../../latest.json', $latestJson);

        return self::SUCCESS;
    }
}
