<?php

namespace Analogic\CryptocurrencyBundle\Dogecoind;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindBase;

final class Dogecoind extends BitcoindBase
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
}