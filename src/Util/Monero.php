<?php

namespace Analogic\CryptocurrencyBundle\Util;

class Monero
{
    public static function wholeToAtomic(string $btc): int
    {
        $btc = sprintf('%.12f', $btc); // bcmath can't work with exponential notation. thats just sad thing here :(
        return intval(bcmul($btc, "1000000000000", 12));
    }

    public static function atomicToWhole(int $satoshi): string
    {
        return bcdiv($satoshi, "1000000000000", 12);
    }
}
