<?php

namespace Analogic\CryptocurrencyBundle\Lnd;

use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;
use Analogic\CryptocurrencyBundle\Util\Bitcoin;
use LightningSale\LndRest\Model\SendCoinsRequest;
use LightningSale\LndRest\Model\SendRequest;
use LightningSale\LndRest\Resource\LndClient;

abstract class Lnd implements DaemonInterface
{
    private $c;

    public function __construct(LndClient $c)
    {
        $this->c = $c;
    }

    public function pay(TransactionRequestList $paymentRequestList): string
    {
        throw new \RuntimeException("Please use pay single, multiple transactions not supported");
    }

    public function paySingle(TransactionRequest $payment): string
    {
        $req = $this->c->decodePayReq($payment->getAddress());

        if ($req->getExpiry() < time()) {
            throw new \RuntimeException("Already expired request. Expiry: ".$req->getExpiry()." time now: ".time());
        }

        if ($req->getNumSatoshis() !== $payment->getAtomic()) {
            throw new \RuntimeException("Invalid amount in paymentRequest: ".$req->getNumSatoshis()." vs user requested: ".$payment->getAtomic());
        }

        //$scr = new SendRequest()
        //$this->c->sendCoins()

        return uniqid();
    }

    public function getBalance(): int
    {
        return Bitcoin::wholeToAtomic($this->c->walletBalance()->getConfirmedBalance());
    }

    public function addInvoice(int $atomic, string $memo): string
    {

    }
}