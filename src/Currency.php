<?php

namespace Analogic\CryptocurrencyBundle;

use Analogic\CryptocurrencyBundle\Util\Bitcoin;
use Analogic\CryptocurrencyBundle\Util\Dash;
use Analogic\CryptocurrencyBundle\Util\Dogecoin;
use Analogic\CryptocurrencyBundle\Util\Ethereum;
use Analogic\CryptocurrencyBundle\Util\Litecoin;
use Analogic\CryptocurrencyBundle\Util\Monero;

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
    
    public static function wholeToAtomic(string $currency, string $amount): int
    {
        switch ($currency) {
            case self::LTC: return Litecoin::wholeToAtomic($amount);
            case self::BTC: return Bitcoin::wholeToAtomic($amount);
            case self::DASH: return Dash::wholeToAtomic($amount);
            case self::DOGE: return Dogecoin::wholeToAtomic($amount);
            case self::XMR: return Monero::wholeToAtomic($amount);
            case self::ETH: return Ethereum::wholeToAtomic($amount);
            default: throw new \RuntimeException("Currency $currency not implemented");
        }    
    }

    public static function atomicToWhole(string $currency, int $amount): string
    {
        switch ($currency) {
            case self::LTC: return Litecoin::atomicToWhole($amount);
            case self::BTC: return Bitcoin::atomicToWhole($amount);
            case self::DASH: return Dash::atomicToWhole($amount);
            case self::DOGE: return Dogecoin::atomicToWhole($amount);
            case self::XMR: return Monero::atomicToWhole($amount);
            case self::ETH: return Ethereum::atomicToWhole($amount);
            default: throw new \RuntimeException("Currency $currency not implemented");
        }
    }
}