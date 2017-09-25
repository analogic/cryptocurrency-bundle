<?php

namespace Analogic\CryptocurrencyBundle\Ethereumd;

use Analogic\CryptocurrencyBundle\Bitcoind\TransactionFactoryInterface;
use Analogic\CryptocurrencyBundle\Transaction\Move;
use Analogic\CryptocurrencyBundle\Transaction\Transaction;
use Analogic\CryptocurrencyBundle\Util\Ethereum;

class TransactionFactory implements TransactionFactoryInterface
{
    public function createFromString(string $data): Transaction
    {
        $parsed = json_decode($data);
        if(empty($parsed)) {
            throw new \RuntimeException("Can't parse incoming JSON \"$data\": ".json_last_error_msg());
        }

        return $this->createFromData($parsed);
    }

    public function createFromData(\stdClass $data): Transaction
    {
        /*
         * {
      blockHash: "0xae024e23b56d119bf7f35d51fa64b8d17e70445dc0cb8bab5efec212ce61e8e9",
      blockNumber: 4287045,
      from: "0xdc3e750be35499859a4b5db1af354a429921722d",
      gas: 21000,
      gasPrice: 21000000000,
      hash: "0xf45e6f79ea50842fa0c976a3e699949f5e2581693f09180308372861fc5f12ab",
      input: "0x",
      nonce: 79,
      r: "0xa772825f661dca57a92b509e49fec3b96475b06055e4e92fcef93763419f8f92",
      s: "0x5dd6305a6d4b76be0ddeb3c243c74cda455b841f7533cb1903526c94c0931d7f",
      to: "0x76384b39103b16bb03cc401bf3d82ef9bf88e308",
      transactionIndex: 29,
      v: "0x25",
      value: 184400000000000000
  }
         */

        $transaction = new Transaction();
        $transaction->setConfirmations(0); // we need to handle it later
        $transaction->setBlock(Ethereum::bchexdec($data->blockNumber));
        $transaction->setTxid($data->hash);

        $move = new Move();
        $move->setVout(0);
        $move->setAddress($data->to === null ? "" : $data->to);
        $move->setAtomic(Ethereum::convertRawToGwei((string)$data->value));

        $transaction->getMoves()->push($move);

        return $transaction;
    }

}