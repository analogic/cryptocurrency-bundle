<?php

namespace Analogic\CryptocurrencyBundle\Util;

class BitcoinCash
{
    public static function wholeToAtomic(string $btc): int
    {
        $btc = sprintf('%.8f', $btc); // bcmath can't work with exponential notation. thats just sad thing here :(
        return intval(bcmul($btc, "100000000", 8));
    }

    public static function atomicToWhole(int $satoshi): string
    {
        return bcdiv($satoshi, "100000000", 8);
    }
}
