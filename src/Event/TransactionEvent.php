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

    public function __construct(Transaction $transaction, string $currency, string $data)
    {
        $this->transaction = $transaction;
        $this->currency = $currency;
        $this->data = $data;
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