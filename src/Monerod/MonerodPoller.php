<?php

namespace Analogic\CryptocurrencyBundle\Monerod;

use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MonerodPoller {

    protected $monerod;
    protected $eventDispatcher;

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
        return $this->monerod->getBulkPayments($paymentIds);
    }
}

