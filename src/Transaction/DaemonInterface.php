<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

interface DaemonInterface
{
    public function getBalance(): int;
    public function pay(TransactionRequestList $paymentRequestList): string;
    public function paySingle(TransactionRequest $paymentRequest): string;
}