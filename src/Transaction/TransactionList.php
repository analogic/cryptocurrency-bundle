<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class TransactionList implements \ArrayAccess, \Iterator
{
    private $transactions;

    public function __construct(array $givenArray = array())
    {
        $this->transactions = $givenArray;
    }

    public function push(Transaction $paymentRequest)
    {
        $this->transactions[] = $paymentRequest;
    }

    public function rewind()
    {
        return reset($this->transactions);
    }

    public function current(): ?Transaction
    {
        return current($this->transactions);
    }

    public function key()
    {
        return key($this->transactions);
    }

    public function next()
    {
        return next($this->transactions);
    }

    public function valid()
    {
        return key($this->transactions) !== null;
    }

    public function offsetSet($offset, $value)
    {
        $this->transactions[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->transactions[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->transactions[$offset]);
    }

    public function offsetGet($offset): ?Transaction
    {
        return isset($this->transactions[$offset]) ? $this->transactions[$offset] : null;
    }
}