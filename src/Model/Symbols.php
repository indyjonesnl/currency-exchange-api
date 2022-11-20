<?php

namespace App\Model;

final class Symbols
{
    public const EUR = 'EUR';
    public const SYMBOLS = [
        'BAM' => 'Convertible Mark',
        'BGN' => 'Bulgarian Lev',
        'BYN' => 'Belarusian Ruble',
        'CAD' => 'Canadian Dollar',
        'CHF' => 'Swiss Franc',
        'CZK' => 'Czech Koruna',
        'DKK' => 'Danish Krone',
        self::EUR => 'Euro',
        'GBP' => 'Pound Sterling',
        'HRK' => 'Croatian Kuna',
        'HUF' => 'Hungarian Forint',
        'MKD' => 'Macedonian Denar',
        'NOK' => 'Norwegian Krone',
        'PLN' => 'Poland Złoty',
        'RON' => 'Romanian Leu',
        'RSD' => 'Serbian Dinar',
        'RUB' => 'Russian Ruble',
        'SEK' => 'Swedish Krona',
        'TRY' => 'Turkish Lira',
        'UAH' => 'Ukrainian Hryvnia',
        'USD' => 'US Dollar',
    ];
    public const SYMBOL_KEYS = [
        'BAM',
        'BGN',
        'BYN',
        'CAD',
        'CHF',
        'CZK',
        'DKK',
        self::EUR,
        'GBP',
        'HRK',
        'HUF',
        'MKD',
        'NOK',
        'PLN',
        'RON',
        'RSD',
        'RUB',
        'SEK',
        'TRY',
        'UAH',
        'USD',
    ];
    public const SYMBOLS_COMBINED = 'BAM,BGN,BYN,CAD,CHF,CZK,DKK,EUR,GBP,HRK,HUF,MKD,NOK,PLN,RON,RSD,RUB,SEK,TRY,UAH,USD';
}
