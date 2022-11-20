<?php

namespace App\Controller;

use App\Model\Symbols;
use App\Service\CurrencyExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CurrencyController extends AbstractController
{
    public function __construct(
        private readonly CurrencyExchangeService $currencyExchangeService,
    ) {}

    #[Route('/symbols')]
    public function symbols(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'symbols' => Symbols::SYMBOLS,
        ]);
    }

    #[Route('/latest')]
    public function latest(Request $request): JsonResponse
    {
        $base = $this->getValidCurrency($request->query->getAlpha('base')) ?? Symbols::EUR;
        $symbols = (string) ($request->query->get('symbols') ?? Symbols::SYMBOLS_COMBINED);

        return new JsonResponse($this->currencyExchangeService->getExchangeRates($base, $symbols));
    }

    private function getValidCurrency(?string $base): ?string
    {
        if ($base === null) {
            return null;
        }

        if (!\in_array($base, Symbols::SYMBOL_KEYS)) {
            return null;
        }

        return $base;
    }
}
