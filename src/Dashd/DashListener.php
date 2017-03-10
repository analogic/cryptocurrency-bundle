<?php

namespace Analogic\CryptocurrencyBundle\Dashd;

use Analogic\CryptocurrencyBundle\Bitcoind\BitcoindListener;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;

class DashListener extends BitcoindListener
{
    protected function newTransactionEvent($data)
    {
        return new TransactionEvent('DASH', $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent('DASH', $data);
    }
}