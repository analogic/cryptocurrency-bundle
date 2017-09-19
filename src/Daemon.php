<?php

namespace Analogic\CryptocurrencyBundle;

use Analogic\CryptocurrencyBundle\Bitcoind\Bitcoind;
use Analogic\CryptocurrencyBundle\BitcoinCashd\BitcoinCashd;
use Analogic\CryptocurrencyBundle\Dashd\Dashd;
use Analogic\CryptocurrencyBundle\Dogecoind\Dogecoind;
use Analogic\CryptocurrencyBundle\Ethereumd\Ethereumd;
use Analogic\CryptocurrencyBundle\Litecoind\Litecoind;
use Analogic\CryptocurrencyBundle\Monerod\Monerod;
use Analogic\CryptocurrencyBundle\Transaction\DaemonInterface;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequest;
use Analogic\CryptocurrencyBundle\Transaction\TransactionRequestList;

class Daemon
{
    public $monerod;
    public $bitcoind;
    public $bitcoinCashd;
    public $ethereumd;
    public $litecoind;
    public $dogecoind;
    public $dashd;

    public function __construct(Dashd $dashd, Bitcoind $bitcoind, BitcoinCashd $bitcoinCashd, Ethereumd $ethereumd, Litecoind $litecoind, Dogecoind $dogecoind, Monerod $monerod)
    {
        $this->dashd = $dashd;
        $this->bitcoinCashd = $bitcoinCashd;
        $this->bitcoind = $bitcoind;
        $this->ethereumd = $ethereumd;
        $this->litecoind = $litecoind;
        $this->dogecoind = $dogecoind;
        $this->monerod = $monerod;
    }

    public function paySingle(string $currency, TransactionRequest $request): string
    {
        return $this->getCurrencyDaemon($currency)->paySingle($request);
    }

    public function pay(string $currency, TransactionRequestList $transactionRequestList): string
    {
        return $this->getCurrencyDaemon($currency)->pay($transactionRequestList);
    }

    public function getBalance(string $currency): int
    {
        return $this->getCurrencyDaemon($currency)->getBalance();
    }

    public function getCurrencyDaemon(string $currency): DaemonInterface
    {
        switch ($currency) {
            case 'DASH': return $this->dashd;
            case 'BTC': return $this->bitcoind;
            case 'BCH': return $this->bitcoinCashd;
            case 'ETH': return $this->ethereumd;
            case 'LTC': return $this->litecoind;
            case 'DOGE': return $this->dogecoind;
            case 'XMR': return $this->monerod;
            default: throw new \RuntimeException("$currency daemon not found");
        }
    }
}