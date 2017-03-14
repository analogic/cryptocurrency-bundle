<?php

namespace Analogic\CryptocurrencyBundle\Monero;

class Transaction extends \Analogic\CryptocurrencyBundle\Transaction\Transaction
{
    protected $height;
    protected $moneroPaymentId;
    protected $atomic;

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    public function getMoneroPaymentId(): string
    {
        return $this->moneroPaymentId;
    }

    public function setMoneroPaymentId(string $moneroPaymentId)
    {
        $this->moneroPaymentId = $moneroPaymentId;
    }

    public function getAtomic(): int
    {
        return $this->atomic;
    }

    public function setAtomic(int $atomic)
    {
        $this->atomic = $atomic;
    }


}