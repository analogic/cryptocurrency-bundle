<?php

namespace Analogic\CryptocurrencyBundle\Event;

use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Symfony\Component\EventDispatcher\Event;

class TransactionEvent extends Event
{
    const NAME = 'transaction';

    protected $currency;
    protected $data;

    private $transaction;

    public function __construct(string $currency, string $data)
    {
        $this->currency = $currency;
        $this->data = $data;
        $parsed = json_decode($data);

        if(empty($parsed)) {
            throw new \RuntimeException("Can't parse incoming JSON \"$data\": ".json_last_error_msg());
        }

        $this->transaction = new Transaction($parsed);
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}