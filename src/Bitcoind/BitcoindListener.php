<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class BitcoindListener extends BitcoindListenerBase {
    public function __construct(
        $dsn,
        $account,
        TransactionFactory $transactionFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($dsn, $account, $transactionFactory, $eventDispatcher);
    }
}

