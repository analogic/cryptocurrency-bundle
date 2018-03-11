<?php

namespace Analogic\CryptocurrencyBundle\BitcoinCashd;

use Analogic\CryptocurrencyBundle\Transaction\Move;
use Analogic\CryptocurrencyBundle\Util\Bitcoin;

class TransactionFactory extends \Analogic\CryptocurrencyBundle\Bitcoind\TransactionFactory
{
    protected function importMove($data): Move
    {
        $move = new Move();
        $move->setAccount($data->account);
        $move->setCategory($data->category);
        $move->setAddress($data->address);

        $move->setAtomic(Bitcoin::wholeToAtomic($data->amount));
        $move->setVout(intval($data->vout));

        return $move;
    }
}