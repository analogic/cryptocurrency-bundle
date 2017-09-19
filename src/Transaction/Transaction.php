<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class Transaction
{
    protected $txid;
    protected $confirmations = 0;
    protected $replaceable;
    protected $moves;
    protected $block = 0;

    public function __construct()
    {
        $this->moves = new MoveList();
    }

    public function isValid()
    {
        return !(empty($this->txid) || empty($this->address) || empty($this->satoshi));
    }

    public function getTxid(): string
    {
        return $this->txid;
    }

    public function setTxid(string $txid)
    {
        $this->txid = $txid;
    }

    public function getConfirmations(): int
    {
        return $this->confirmations;
    }

    public function setConfirmations(int $confirmations)
    {
        $this->confirmations = $confirmations;
    }

    public function getMoves(): MoveList
    {
        return $this->moves;
    }

    public function setMoves(MoveList $moveList): void
    {
        $this->moves = $moveList;
    }

    public function getBlock(): int
    {
        return $this->block;
    }

    public function setBlock(int $block)
    {
        $this->block = $block;
    }
}