<?php

namespace App\Console\DomainModel;

class Car implements CarInterface
{
    private string $vin;
    private string $mark;

    /**
     * Car constructor.
     * @param string $vin
     * @param string $mark
     */
    public function __construct(string $vin, string $mark)
    {
        $this->vin = $vin;
        $this->mark = $mark;
    }

    /**
     * @return string
     */
    public function getVin(): string
    {
        return $this->vin;
    }

    /**
     * @return string
     */
    public function getMark(): string
    {
        return $this->mark;
    }
}
