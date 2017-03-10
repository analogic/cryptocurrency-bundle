<?php

namespace Analogic\CryptocurrencyBundle\Transaction;

class MoveList implements \ArrayAccess, \Iterator
{
    private $moves;

    public function __construct(array $givenArray = array())
    {
        $this->moves = $givenArray;
    }

    public function count()
    {
        return count($this->moves);
    }

    public function push(Move $move)
    {
        $this->moves[$move->getVout()] = $move;
    }

    public function merge(MoveList $movesList)
    {
        foreach($movesList as $move) {
            $this->push($move);
        }
    }

    public function filterByAccount(string $account): MoveList
    {
        $output = new MoveList();

        foreach($this->moves as $move) {
            if($move->getAccount() === $account) {
                $output->push($move);
            }
        }

        return $output;
    }

    public function rewind()
    {
        return reset($this->moves);
    }

    public function current(): ?Move
    {
        return current($this->moves);
    }

    public function key()
    {
        return key($this->moves);
    }

    public function next()
    {
        return next($this->moves);
    }

    public function valid()
    {
        return key($this->moves) !== null;
    }

    public function offsetSet($offset, $value)
    {
        $this->moves[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->moves[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->moves[$offset]);
    }

    public function offsetGet($offset): ?Move
    {
        return isset($this->moves[$offset]) ? $this->moves[$offset] : null;
    }
}

