<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

final class Bitcoind extends BitcoindBase {
    public function getNewAddress(): string
    {
        $address = parent::getNewAddress();
        $wAddress = $this->execute('addwitnessaddress', $address)->result;
        $this->execute('setaccount', [$address, $this->account]);

        return $wAddress;
    }
}

