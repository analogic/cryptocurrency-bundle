<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;
use Analogic\CryptocurrencyBundle\Monero\Monerod;
use Socket\Socket;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MoneroListener {

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

    public function listen()
    {
        // polling

        $lastBlockHeight = 0;

        while (true) {

            if($lastBlockHeight)

            // TODO retrieve active payments_ids
            $paymentIds = [];

            $list = $this->monerod->getBulkPayments($paymentIds);

            foreach($list as)

            sleep(10);
        }

        $socket = new Socket($this->host, $this->port);

        while (!$socket->eof()) {
            $data = $socket->readLine();
            if($data == false) continue;

            if ($data[0] == '{') {
                $event = $this->newTransactionEvent($data);
                $moves = $event->getTransaction()->getMoves()->filterByAccount($this->account);

                if(count($moves) === 0) {
                    continue;
                }

                $event->getTransaction()->setMoves($moves);
                $this->eventDispatcher->dispatch($event::NAME, $event);
            } else {
                $event = $this->newBlockEvent($data);
                $this->eventDispatcher->dispatch($event::NAME, $event);
            }
        }
    }

    protected function newTransactionEvent($data)
    {
        return new TransactionEvent($this->transactionFactory->createFromString($data), 'BTC', $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent('BTC', $data);
    }
}

