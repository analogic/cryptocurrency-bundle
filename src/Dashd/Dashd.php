<?php

namespace Analogic\CryptocurrencyBundle\Dashd;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindBase;
use Analogic\CryptocurrencyBundle\Bitcoind\TransactionFactoryInterface;

final class Dashd extends BitcoindBase
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