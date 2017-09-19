<?php

namespace Analogic\CryptocurrencyBundle\Bitcoind;

final class Bitcoind extends BitcoindBase {

    public function getNewAddress(): string
    {
        // temporary segwit address generation fix
        $address = parent::getNewAddress();
        $wAddress = $this->execute('addwitnessaddress', $address)->result;
        $this->execute('setaccount', [$wAddress, $this->account]);

        return $wAddress;
    }
}

