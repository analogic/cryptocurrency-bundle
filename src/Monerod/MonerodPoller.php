<?php

namespace Analogic\CryptocurrencyBundle\Monerod;

use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;
use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MonerodPoller {

    protected $monerod;
    public $eventDispatcher;

    private $lastBlockHeight = 0;

    public function __construct(Monerod $monerod, EventDispatcherInterface $eventDispatcher)
    {
        $this->monerod = $monerod;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function checkBlockHeight()
    {
        $currentBlockHeight = $this->monerod->getHeight();
        if ($this->lastBlockHeight === $currentBlockHeight) { return; }

        $this->lastBlockHeight = $currentBlockHeight;

        $event = new BlockEvent('XMR', (string) $currentBlockHeight);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }

    public function checkPayments(array $paymentIds) {
        $txs = $this->monerod->getBulkPayments($paymentIds);
        foreach($txs as $tx) {
            $event = new TransactionEvent($tx, 'XMR', $tx->getTxid());
            $this->eventDispatcher->dispatch($event::NAME, $event);
        }
    }
}

