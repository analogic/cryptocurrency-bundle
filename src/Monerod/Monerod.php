<?php

namespace Analogic\CryptocurrencyBundle\Monerod;

use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;

final class Monerod implements DaemonInterface
{
    protected $dsn;
    protected $auth;

    public function __construct(string $dsn)
    {
        if(preg_match('~//(.+?)@~', $dsn, $match)) {
            $this->dsn = preg_replace('~//(.+?)@~', '//', $dsn);
            $this->auth = $match[1];
        } else {
            $this->dsn = $dsn;
        }
    }

    public function execute($method, $params = null, string $id = null): \stdClass
    {
        $ch = curl_init($this->dsn);

        if (null === $params || "" == $params) {
            $params = array();
        } elseif (!empty($params) && !is_array($params)) {
            $params = array($params);
        }

        $json = json_encode(['method' => $method, 'jsonrpc' => '2.0', 'params' => $params, 'id' => $id]);

        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
            CURLOPT_USERPWD        => $this->auth,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30
        ));

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if (false === $response) {
            throw new \Exception('The server is not available.');
        }

        $stdClass = json_decode($response);

        if (!empty($stdClass->error)) {
            throw new \Exception($stdClass->error->message, $stdClass->error->code);
        }

        return $stdClass;
    }

    public function getBulkPayments(array $paymentIds): TransactionList
    {
        $transactions = new TransactionList();

        $result = $this->execute('get_bulk_payments', ['payment_ids' => $paymentIds])->result;
        if(!property_exists($result, 'payments')) {
            return $transactions;
        }

        foreach($result->payments as $tx) {
            $transaction = new Transaction();

            $transaction->setTxid($tx->tx_hash);
            $transaction->setMoneroPaymentId($tx->payment_id);
            $transaction->setAtomic($tx->amount);
            $transaction->setHeight($tx->block_height);

            $transactions->push($transaction);
        }

        return $transactions;
    }

    public function getHeight(): int
    {
        return $this->execute('getheight')->result->height;
    }

    public function getBalance(): int
    {
        return $this->execute('getbalance')->result->balance;
    }

    public function getAddress(): string
    {
        return $this->execute('getaddress')->result->address;
    }

    public function splitIntegratedAddress($address): \stdClass
    {
        // returns ['payment_id' => ..., 'standard_address' => ...]
        return $this->execute('split_integrated_address', ['integrated_address' => $address])->result;
    }

    public function makeIntegratedAddress(string $paymentId = ""): string
    {
        return  $this->execute('make_integrated_address', ['payment_id' => $paymentId])->result->integrated_address;
    }


    public function pay(TransactionRequestList $paymentRequestList): string
    {
        throw new \RuntimeException("Please use pay single, multiple transactions not supported because of https://github.com/monero-project/monero/issues/1505");
    }

    public function paySingle(TransactionRequest $paymentRequest): string
    {
        $outputs = [];

        $output = ['amount' => $paymentRequest->getAtomic(), 'address' => $paymentRequest->getAddress()];
        if (!empty($paymentRequest->getPaymentId())) {
            $output['payment_id'] = $paymentRequest->getPaymentId();
        }
        $outputs[] = $output;

        $result = $this->execute('transfer', ['destinations' => $outputs, 'mixin' => 0, 'get_tx_key' => true, 'unlock_time' => 0]);

        return $result->result->tx_hash;
    }
}