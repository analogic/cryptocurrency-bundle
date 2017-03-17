<?php

namespace Analogic\CryptocurrencyBundle\Ethereumd;

use Analogic\CryptocurrencyBundle\Currency;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EthereumdPoller {

    protected $ethereumd;
    public $eventDispatcher;

    private $lastBlockHeight = 0;

    public function __construct(Ethereumd $ethereumd, EventDispatcherInterface $eventDispatcher)
    {
        $this->ethereumd = $ethereumd;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function checkBlockHeight()
    {
        $currentBlockHeight = $this->ethereumd->getHeight();
        if ($this->lastBlockHeight === $currentBlockHeight) { return; }

        $this->lastBlockHeight = $currentBlockHeight;

        $event = new BlockEvent(Currency::ETH, (string) $currentBlockHeight);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }

    public function checkPayments(array $paymentIds) {
        $txs = $this->ethereumd->getBulkPayments($paymentIds);
        foreach($txs as $tx) {
            $event = new TransactionEvent($tx, Currency::ETH, $tx->getTxid());
            $this->eventDispatcher->dispatch($event::NAME, $event);
        }
    }
}

