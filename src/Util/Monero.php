<?php

namespace Analogic\CryptocurrencyBundle\Util;

class Monero
{
    public static function wholeToAtomic(string $btc): int
    {
        return intval(bcmul($btc, "1000000000000", 12));
    }

    public static function atomicToWhole(int $satoshi): string
    {
        return bcdiv($satoshi, "1000000000000", 12);
    }

    public static function isAddress(string $address): bool
    {
        // simplified test, since rpc api is missing
        // source http://monero.stackexchange.com/questions/1601/how-to-perform-a-simple-verification-of-a-monero-address-with-a-regular-expressi
        return preg_match('~^4[0-9AB][123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{93}$i~', $address);
    }
}