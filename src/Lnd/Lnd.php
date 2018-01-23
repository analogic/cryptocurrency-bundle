<?php

namespace Analogic\CryptocurrencyBundle\Lnd;

use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;
use Analogic\CryptocurrencyBundle\Util\Bitcoin;
use LightningSale\LndClient\Model\AddInvoiceResponse;
use LightningSale\LndClient\RestClient;

abstract class Lnd implements DaemonInterface
{
    private $c;

    public function __construct(RestClient $c)
    {
        $this->c = $c;
    }

    public function pay(TransactionRequestList $paymentRequestList): string
    {
        throw new \RuntimeException("Please use pay single, multiple transactions not supported");
    }

    /**
     * @param TransactionRequest $payment
     * @return string TXID
     */
    public function paySingle(TransactionRequest $payment): string
    {
        $req = $this->c->decodePayReq($payment->getAddress());

        if ($req->getExpiry() < time()) {
            throw new \RuntimeException("Already expired request. Expiry: ".$req->getExpiry()." time now: ".time());
        }

        if ($req->getNumSatoshis() !== $payment->getAtomic()) {
            throw new \RuntimeException("Invalid amount in paymentRequest: ".$req->getNumSatoshis()." vs user requested: ".$payment->getAtomic());
        }

        $sendResponse = $this->c->sendPaymentRequest($payment->getAddress());

        if (strlen($sendResponse->getPaymentError()) !== 0) {
            throw new \RuntimeException($sendResponse->getPaymentError());
        }

        return $sendResponse->getPaymentPreimage();
    }

    public function getBalance(): int
    {
        return Bitcoin::wholeToAtomic($this->c->walletBalance()->getConfirmedBalance());
    }

    public function addInvoice(int $atomic, string $memo): AddInvoiceResponse
    {
        return $this->c->addInvoice($memo, $atomic);
    }
}