<?php

namespace Analogic\CryptocurrencyBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class BlockEvent extends Event
{
    const NAME = 'block';

    protected $data;
    protected $currency;

    public function __construct(string $currency, string $data)
    {
        $this->currency = $currency;
        $this->data = $data;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}