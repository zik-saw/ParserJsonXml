<?php

namespace App\Console\DomainModel;

interface CarInterface
{
    /**
     * @return string
     */
    public function getVin(): string;

    /**
     * @return string
     */
    public function getMark(): string;
}
