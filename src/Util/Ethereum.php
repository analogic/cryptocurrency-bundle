<?php

namespace Analogic\CryptocurrencyBundle\Util;

class Ethereum
{
    // We use gwei internally because wei might be hitting bigint(db) or int64(php)
    public static function wholeToAtomic(string $btc): int
    {
        return intval(bcmul($btc, "1000000000", 9));
    }

    public static function atomicToWhole(int $satoshi): string
    {
        return bcdiv($satoshi, "1000000000", 9);
    }

    public static function isAddress(string $address): bool
    {
        // source https://github.com/ethereum/web3.js/blob/master/lib/utils/utils.js isAddress
        // and simplified - we will not implement sha3
        return preg_match('~^(0x)?[0-9a-f]{40}$i~', $address);
    }
}