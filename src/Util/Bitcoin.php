<?php

namespace Analogic\CryptocurrencyBundle\Util;

class Bitcoin
{
    public static function wholeToAtomic(string $btc): int
    {
        return intval(bcmul($btc, "100000000", 8));
    }

    public static function atomicToString(int $satoshi): string
    {
        return bcdiv($satoshi, "100000000", 8);
    }
}