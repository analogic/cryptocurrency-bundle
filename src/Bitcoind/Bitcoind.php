<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

final class Bitcoind extends BitcoindBase {

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

