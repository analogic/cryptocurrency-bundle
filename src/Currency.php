<?php

namespace Analogic\CryptocurrencyBundle;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

final class Currency
{
    const LTC = 'LTC';
    const BTC = 'BTC';
    const USD = 'USD';
    const DASH = 'DASH';
    const DOGE = 'DOGE';
    const XMR = 'XMR';
    const ETH = 'ETH';

    public static function getCurrencies(): array
    {
        return [
            self::LTC,
            self::BTC,
            self::USD,
            self::DASH,
            self::DOGE,
            self::XMR,
            self::ETH
        ];
    }
}