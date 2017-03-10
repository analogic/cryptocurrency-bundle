<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

use Analogic\CryptocurrencyBundle\Util\Bitcoin;
use Analogic\CryptocurrencyBundle\Transaction\Move;
use Analogic\CryptocurrencyBundle\Transaction\Transaction;

class TransactionFactory
{
    public function createFromData(\stdClass $data): Transaction
    {
        if(property_exists($data, "address")) {
            return $this->importListTransactionFormat($data);
        } else {
            return $this->importGetTransactionFormat($data);
        }
    }

    public function importListTransactionFormat($data): Transaction
    {
        /*
         * listtransactions format
         *
         * Array (
            [account] => boxaccount
            [address] => 1FVcXz4UNT7GepniqyK7sQt5Y6WgtAyXAR
            [category] => send
            [amount] => -0.133
            [vout] => 1
            [fee] => -0.0001
            [confirmations] => 115
            [blockhash] => 000000000000000008cf98d34f80ec464e49c6110b38f86d31f5296ac2af42de
            [blockindex] => 329
            [blocktime] => 1452212945
            [txid] => 9c6a15aeea6a45e119e3d69b67dd057c8ce20d4869edb86a61e036f806e97850
            [walletconflicts] => Array()
            [time] => 1452212370
            [timereceived] => 1452212370

            [bip125-replaceable] => no
         * )
         */
        
        $transaction = new Transaction();
        $transaction->setConfirmations(intval($data->confirmations));
        $transaction->setTxid($data->txid);

        $transaction->getMoves()->push($this->importMove($data));

        return $transaction;
    }

    public function importGetTransactionFormat($data): Transaction
    {
        /*
         * transaction details
        [amount] => -0.047
        [fee] => -0.00082937
        [confirmations] => 1069
        [blockhash] => 000000000000000000dde2497a590eb6271ec19ffe03efe919a29411f7b0ba24
        [blockindex] => 26
        [blocktime] => 1486008008
        [txid] => c75e8dd63c2460253dba0863a828c3effe68de92bf622808c8d361295ba317d9
        [walletconflicts] => Array()
        [time] => 1486007981
        [timereceived] => 1486007981
        [bip125-replaceable] => no
        [comment] => Payment to box owner (BOX:u47phsd6wbqwmvhzr5cjc3cx), origin TxID: b7d9cb90463bc2fb00154068d906da18f759479f075c51e0bdd8669eb56d7ab8
        [details] => Array
        (
            [0] => Array
                (
                    [account] => boxaccount
                    [address] => 19LZdHtqvm8MmRFXNxDKdXJUuYMw1kGWvK
                    [category] => send
                    [amount] => -0.047
                    [vout] => 1
                    [fee] => -0.00082937
                    [abandoned] =>
                )

        )
        [hex] => ...
         */

        $transaction = new Transaction();
        $transaction->setConfirmations(intval($data->confirmations));
        $transaction->setTxid($data->txid);

        foreach($data->details as $detail) {
            $transaction->getMoves()->push($this->importMove($detail));
        }
        return $transaction;
    }

    private function importMove($data): Move
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