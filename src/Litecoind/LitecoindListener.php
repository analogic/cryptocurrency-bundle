<?php

namespace Analogic\CryptocurrencyBundle\Litecoind;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindListenerBase;
use Analogic\CryptocurrencyBundle\Currency;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;

final class LitecoindListener extends BitcoindListenerBase
{
    protected function newTransactionEvent($data)
    {
        return new TransactionEvent($this->transactionFactory->createFromString($data), Currency::LTC, $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent(Currency::LTC, $data);
    }
}