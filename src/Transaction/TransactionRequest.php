<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class TransactionRequest
{
    protected $address;
    protected $atomic;
    protected $comment = '';

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