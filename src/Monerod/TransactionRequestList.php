<?php

namespace Analogic\CryptocurrencyBundle\Monerod;

class TransactionRequestList implements \ArrayAccess, \Iterator
{
    private $paymentRequests;

    public function __construct(array $givenArray = array())
    {
        $this->paymentRequests = $givenArray;
    }

    public function count()
    {
        return count($this->paymentRequests);
    }

    public function push(TransactionRequest $paymentRequest)
    {
        $this->paymentRequests[] = $paymentRequest;
    }

    public function merge(TransactionRequestList $paymentRequestList)
    {
        foreach($paymentRequestList as $paymentRequest) {
            $this->push($paymentRequest);
        }
    }

    public function rewind()
    {
        return reset($this->paymentRequests);
    }

    public function current(): ?TransactionRequest
    {
        return current($this->paymentRequests);
    }

    public function key()
    {
        return key($this->paymentRequests);
    }

    public function next()
    {
        return next($this->paymentRequests);
    }

    public function valid()
    {
        return key($this->paymentRequests) !== null;
    }

    public function offsetSet($offset, $value)
    {
        $this->paymentRequests[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->paymentRequests[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->paymentRequests[$offset]);
    }

    public function offsetGet($offset): ?TransactionRequest
    {
        return isset($this->paymentRequests[$offset]) ? $this->paymentRequests[$offset] : null;
    }
}

