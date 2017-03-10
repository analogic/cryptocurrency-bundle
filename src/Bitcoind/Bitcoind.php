<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Analogic\CryptocurrencyBundle\Transaction\TransactionList;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;
use Analogic\CryptocurrencyBundle\Util\Bitcoin;

class Bitcoind {

    protected $dsn;
    protected $account;
    protected $estimateFeesBlocks;

    public function __construct(string $dsn, string $account, int $estimateFeesBlocks)
    {
        $this->dsn = $dsn;
        $this->account = $account;
        $this->estimateFeesBlocks = $estimateFeesBlocks;
    }

    public function execute($method, $params = null, string $id = null): \stdClass
    {
        $ch = curl_init($this->dsn);

        if (null === $params || "" == $params) {
            $params = array();
        } elseif (!empty($params) && !is_array($params)) {
            $params = array($params);
        }

        $json = json_encode(array('method' => $method, 'params' => $params, 'id' => $id));
        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS     => $json,
        ));
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

    public function validateAddress(string $address): bool
    {
        $result = $this->execute('validateaddress', $address)->result;
        return $result->isvalid;
    }

    public function getBalanceInSatoshi($minconf = 1): int
    {
        $btc = $this->execute('getbalance', $this->account, $minconf)->result;
        return Bitcoin::wholeToAtomic($btc);
    }

    public function getNewAddress(): string
    {
        return $this->execute('getnewaddress', $this->account)->result;
    }

    public function getTransaction(string $txid): ?Transaction
    {
        $rawTransaction = $this->execute('gettransaction', $txid)->result;
        return new Transaction($rawTransaction);
    }

    public function listTransactions($count = 10): TransactionList
    {

        $transactions = new TransactionList();
        $rawTransactions = $this->execute('listtransactions', array($this->account, $count));

        foreach($rawTransactions->result as $t) {
            if($t->category != 'receive') continue;
            $transactions->push(new Transaction($t));
        }
        return $transactions;
    }

    public function setDynamicFees(): void
    {
        if($this->estimateFeesBlocks > 0) {
            $fee = floatval($this->execute('estimatefee', [$this->estimateFeesBlocks])->result);
            if ($fee > 0 && $fee < 0.01) {
                $this->execute('settxfee', [$fee]);
            } else {
                throw new \Exception("Fees out of control: $fee");
            }
        }
    }

    public function pay(TransactionRequestList $paymentRequestList): string
    {
        $outputs = [];

        /** @var TransactionRequest $paymentRequest */
        foreach($paymentRequestList as $paymentRequest) {
            $outputs[$paymentRequest->getAddress()] = Bitcoin::atomicToString($paymentRequest->getAtomic());
        }

        $this->setDynamicFees();

        $result = $this->execute('sendmany', [$this->account, $outputs, 1]);
        return $result->result;
    }

    public function paySingle(TransactionRequest $paymentRequest): string
    {
        $this->setDynamicFees();

        $result = $this->execute('sendfrom', [
            $this->account,
            $paymentRequest->getAddress(),
            Bitcoin::atomicToString($paymentRequest->getAtomic()),
            1,
            $paymentRequest->getComment()
        ]);

        return $result->result;
    }
}

