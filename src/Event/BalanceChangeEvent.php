<?php

namespace Analogic\CryptocurrencyBundle\Event;

use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Symfony\Component\EventDispatcher\Event;

/**
 * used for Ethereum only
 */
class BalanceChangeEvent extends Event
{
    const NAME = 'balance-change';

    protected $currency;
    protected $account;

    protected $confirmedFrom;
    protected $confirmedTo;

    protected $pendingFrom;
    protected $pendingTo;

    public function __construct(int $confirmedFrom, int $confirmedTo, int $pendingFrom, int $pendingTo, string $currency, $account)
    {
        $this->confirmedFrom = $confirmedFrom;
        $this->confirmedTo = $confirmedTo;

        $this->pendingFrom = $pendingFrom;
        $this->pendingTo = $pendingTo;

        $this->currency = $currency;
        $this->account = $account;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getConfirmedFrom(): int
    {
        return $this->confirmedFrom;
    }

    public function getConfirmedTo(): int
    {
        return $this->confirmedTo;
    }

    public function getPendingFrom(): int
    {
        return $this->pendingFrom;
    }

    public function getPendingTo(): int
    {
        return $this->pendingTo;
    }
}