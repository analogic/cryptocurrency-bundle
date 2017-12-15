<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Util\Bitcoin;
use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Analogic\CryptocurrencyBundle\Transaction\TransactionList;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;

abstract class BitcoindBase implements DaemonInterface
{

    protected $dsn;
    protected $account;
    protected $estimateFeesBlocks;
    protected $transactionFactory;
    protected $minconf;

    public function __construct(string $dsn, string $account, int $estimateFeesBlocks, int $minconf = 1, TransactionFactoryInterface $transactionFactory)
    {
        $this->dsn = $dsn;
        $this->account = $account;
        $this->minconf = $minconf;
        $this->estimateFeesBlocks = $estimateFeesBlocks;
        $this->transactionFactory = $transactionFactory;
    }

    protected function execute($method, $params = null, string $id = null): \stdClass
    {
        $ch = curl_init($this->dsn);

        if (null === $params || "" == $params) {
            $params = array();
        } elseif (!empty($params) && !is_array($params)) {
            $params = array($params);
        }

        // floating precission encoding bug
        // php 7.1 changed behaviour see https://wiki.php.net/rfc/precise_float_value, setting -1
        $previousSerializePrecission = ini_get('serialize_precision');
        $previousPrecission = ini_get('precision');

        ini_set('precision', 8);
        ini_set('serialize_precision', 8);

        $json = json_encode(array('method' => $method, 'params' => $params, 'id' => $id));

        ini_set('serialize_precision', $previousSerializePrecission);
        ini_set('precision', $previousPrecission);

        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        if (false === $response) {
            throw new \Exception('The server is not available. ('.curl_error($ch).')');
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

    public function getBalance(?int $minconf = null): int
    {
        $btc = $this->execute('getbalance', [$this->account, $minconf ?? $this->minconf])->result;
        return Bitcoin::wholeToAtomic($btc);
    }

    public function getNewAddress(): string
    {
        return $this->execute('getnewaddress', $this->account)->result;
    }

    public function getHeight(): int
    {
        return $this->execute('getblockcount')->result;
    }

    public function getTransaction(string $txid): ?Transaction
    {
        $rawTransaction = $this->execute('gettransaction', $txid)->result;
        return $this->transactionFactory->createFromData($rawTransaction);
    }

    public function listTransactions($count = 10): TransactionList
    {

        $transactions = new TransactionList();
        $rawTransactions = $this->execute('listtransactions', array($this->account, $count));

        foreach($rawTransactions->result as $t) {
            if($t->category != 'receive') continue;
            $transactions->push($this->transactionFactory->createFromData($t));
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

    public function pay(TransactionRequestList $paymentRequestList, $dynamicFee = false): string
    {
        $outputs = [];

        /** @var TransactionRequest $paymentRequest */
        foreach($paymentRequestList as $paymentRequest) {
            if(!isset($outputs[$paymentRequest->getAddress()])) {
                $outputs[$paymentRequest->getAddress()] = 0;
            }
            $outputs[$paymentRequest->getAddress()] += floatval(Bitcoin::atomicToWhole($paymentRequest->getAtomic()));
        }

        if($dynamicFee) $this->setDynamicFees();

        $result = $this->execute('sendmany', [$this->account, $outputs, 1]);
        return $result->result;
    }

    public function paySingle(TransactionRequest $paymentRequest, $dynamicFee = false): string
    {
        $list = new TransactionRequestList();
        $list->push($paymentRequest);

        return $this->pay($list, $dynamicFee);
    }
}
