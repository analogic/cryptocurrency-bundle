<?php

namespace Analogic\CryptocurrencyBundle\Litecoind;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindListener;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;

class LitecoindListener extends BitcoindListener
{
    protected function newTransactionEvent($data)
    {
        return new TransactionEvent('LTC', $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent('LTC', $data);
    }
}