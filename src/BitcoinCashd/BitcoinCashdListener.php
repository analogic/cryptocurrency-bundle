<?php

namespace Analogic\CryptocurrencyBundle\BitcoinCashd;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindListenerBase;
use Analogic\CryptocurrencyBundle\Currency;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class BitcoinCashdListener extends BitcoindListenerBase
{
    public function __construct(
        $dsn,
        $account,
        TransactionFactory $transactionFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($dsn, $account, $transactionFactory, $eventDispatcher);
    }

    protected function newTransactionEvent($data)
    {
        return new TransactionEvent($this->transactionFactory->createFromString($data), Currency::DASH, $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent(Currency::DASH, $data);
    }
}