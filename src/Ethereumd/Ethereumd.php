<?php

namespace Analogic\CryptocurrencyBundle\Ethereumd;

use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;
use Analogic\CryptocurrencyBundle\Util\Ethereum;

final class Ethereumd implements DaemonInterface
{
    public const PENDING = 'pending';
    public const LATEST = 'latest';
    public const CONFIRMED = 7;

    protected $dsn;

    public $motherAccount;
    public $password;

    public function __construct(string $dsn, string $motherAccount, string $password)
    {
        $this->dsn = $dsn;
        $this->motherAccount = $motherAccount;
        $this->password = $password;
    }

    protected function execute($method, $params = null, string $id = null): \stdClass
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
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 5
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

    public function getBalance($type = self::LATEST): int
    {
        return Ethereum::convertRawToGwei($this->execute('eth_getBalance', [$this->motherAccount, $type])->result);
    }

    public function getBalanceOf(string $address, $type = self::LATEST): int
    {
        return Ethereum::convertRawToGwei($this->execute('eth_getBalance', [$address, $type])->result);
    }

    public function transferAllTo(string $account, string $toAccount, string $password): string
    {
        $balance = Ethereum::bchexdec($this->execute('eth_getBalance', [$account, self::LATEST])->result);
        $gasPrice = Ethereum::bchexdec($this->execute('eth_gasPrice')->result);

        $gas = 21000; // gas price per simple account to account transaction;
        $value = bcsub($balance, bcmul($gas, $gasPrice));

        $payload =
            [
                [
                    'from' => $account,
                    'to' => $toAccount,
                    'gas' => "0x".Ethereum::bcdechex($gas),
                    'gasPrice' => "0x".Ethereum::bcdechex($gasPrice),
                    'value' => "0x".Ethereum::bcdechex($value)
                ],
                $password
            ];

        return $this->execute('personal_sendTransaction', $payload)->result;
    }

    /**
     * @return string[]
     */
    public function listAccounts(): array
    {
        return $this->execute('eth_accounts')->result;
    }

    public function createAccount(string $password): string
    {
        return $this->execute('personal_newAccount', [$password])->result;
    }

    public function getBlockNumber(): int
    {
        return Ethereum::bchexdec(substr($this->execute('eth_blockNumber')->result, 2));
    }

    public function pay(TransactionRequestList $paymentRequestList): string
    {
        throw new \RuntimeException("Please use pay single, multiple transactions not supported");
    }

    public function paySingle(TransactionRequest $paymentRequest): string
    {
        $payload =
        [
            [
                'from' => $paymentRequest->getFrom(),
                'to' => $paymentRequest->getAddress(),
                'value' => Ethereum::convertGweiToRaw($paymentRequest->getAtomic())
            ],
            $paymentRequest->getAccountPassword()
        ];

        return $this->execute('personal_sendTransaction', $payload)->result;
    }
}