<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class Move
{
    protected $address;
    protected $atomic;
    protected $account;
    protected $category;
    protected $vout;

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

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getVout(): int
    {
        return $this->vout;
    }

    public function setVout(int $vout)
    {
        $this->vout = $vout;
    }


}