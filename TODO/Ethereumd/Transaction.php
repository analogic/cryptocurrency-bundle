<?php

namespace Analogic\CryptocurrencyBundle\Ethereumd;

class Transaction extends \Analogic\CryptocurrencyBundle\Transaction\Transaction
{
    protected $height;
    protected $ethereumPaymentId;
    protected $atomic;

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    public function getEthereumPaymentId(): string
    {
        return $this->ethereumPaymentId;
    }

    public function setEthereumPaymentId(string $ethereumPaymentId)
    {
        $this->ethereumPaymentId = $ethereumPaymentId;
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