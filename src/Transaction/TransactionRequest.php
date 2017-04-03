<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class TransactionRequest
{
    protected $address;
    protected $atomic;
    protected $comment = '';

    protected $from; // ehtereum
    protected $accountPassword; // ethereum

    protected $paymentId; // monero

    public function getAccountPassword(): ?string
    {
        return $this->accountPassword;
    }

    public function setAccountPassword(string $accountPassword)
    {
        $this->accountPassword = $accountPassword;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from)
    {
        $this->from = $from;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getAtomic(): int
    {
        return $this->atomic;
    }

    public function setAtomic(int $atomic): void
    {
        $this->atomic = $atomic;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}