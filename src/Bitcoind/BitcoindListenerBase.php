<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Currency;
use Analogic\CryptocurrencyBundle\Event\BlockEvent;
use Analogic\CryptocurrencyBundle\Event\TransactionEvent;
use Socket\Socket;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BitcoindListenerBase {

    protected $host;
    protected $port;
    protected $account;

    public $eventDispatcher;
    public $transactionFactory;

    public function __construct(string $dsn, string $account,  TransactionFactoryInterface $transactionFactory, EventDispatcherInterface $eventDispatcher)
    {
        $arr = explode(':', $dsn);

        $this->host = $arr[0];
        $this->port = intval($arr[1]);
        $this->account = $account;

        $this->eventDispatcher = $eventDispatcher;
        $this->transactionFactory = $transactionFactory;
    }

    public function listen()
    {
        $socket = new Socket($this->host, $this->port);

        while (!$socket->eof()) {
            $data = $socket->readLine();
            if($data == false) continue;

            if ($data[0] == '{') {
                $event = $this->newTransactionEvent($data);
                $moves = $event->getTransaction()->getMoves();

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
        return new TransactionEvent($this->transactionFactory->createFromString($data), Currency::BTC, $data);
    }

    protected function newBlockEvent($data)
    {
        return new BlockEvent(Currency::BTC, $data);
    }
}

