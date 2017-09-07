<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

final class Bitcoind extends BitcoindBase {
    public function getNewAddress(): string
    {
        $address = parent::getNewAddress();
        return $this->execute('addwitnessaddress', $address)->result;
    }
}

