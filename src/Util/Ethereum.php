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

    /* should be used to GETH communication only */
    public static function convertRawToGwei(string $input): int
    {
        return intval(bcdiv(self::bchexdec($input), '1000000000'));
    }

    /* should be used to GETH communication only */
    public static function convertGweiToRaw(int $input): string
    {
        return '0x'.self::bcdechex((string)$input.'000000000');
    }

    public static function bchexdec(string $hex) {
        if(isset($hex[1]) && $hex == 'x') {
            $hex = substr($hex, 2);
        }

        if(strlen($hex) == 1) {
            return hexdec($hex);
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, self::bchexdec($remain)), hexdec($last));
        }
    }

    public static function bcdechex(string $dec) {
        $last = bcmod($dec, 16);
        $remain = bcdiv(bcsub($dec, $last), 16);

        if($remain == 0) {
            return dechex($last);
        } else {
            return self::bcdechex($remain).dechex($last);
        }
    }

    public static function test()
    {
        $num = '0x234c8a3397aab58';
        echo "input: ".$num."\n";
        $res = Ethereum::convertRawToGwei($num);
        echo "output:".($out = Ethereum::convertGweiToRaw($res));
        echo "\n";

        if(self::atomicToWhole($res) != '0.158972490') {
            throw new \Exception('Invalid result');
        }

        if(self::WholeToAtomic('0.158972490') != 158972490) {
            throw new \Exception('Invalid result');
        }

    }
}