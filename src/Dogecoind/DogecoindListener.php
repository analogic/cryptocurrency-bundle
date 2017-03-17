<?php

namespace Analogic\CryptocurrencyBundle\Dogecoind;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindListenerBase;
use Analogic\CryptocurrencyBundle\Currency;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;

final class DogecoindListener extends BitcoindListenerBase
{
    protected function newTransactionEvent($data)
    {
        return new TransactionEvent($this->transactionFactory->createFromString($data), Currency::DOGE, $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent(Currency::DOGE, $data);
    }
}