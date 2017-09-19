<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Transaction\Transaction;

interface TransactionFactoryInterface
{
    public function createFromString(string $data): Transaction;

    public function createFromData(\stdClass $data): Transaction;
}