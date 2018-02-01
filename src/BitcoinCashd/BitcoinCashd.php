<?php

namespace Analogic\CryptocurrencyBundle\BitcoinCashd;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindBase;
use CashAddr\CashAddress;

final class BitcoinCashd extends BitcoindBase
{
    public function __construct(
        $dsn,
        $account,
        $estimateFeesBlocks,
        $minconf = 1,
        TransactionFactory $transactionFactory
    ) {
        parent::__construct($dsn, $account, $estimateFeesBlocks, $minconf, $transactionFactory);
    }

    public function getNewAddress(): string
    {
        $addr = parent::getNewAddress();

        // cashaddr
        if (!preg_match('/^bitcoincash/', $addr)) {
            $addr = CashAddress::encode("bitcoincash", "pubkeyhash", hex2bin($addr));
        }

        return $addr;
    }
}