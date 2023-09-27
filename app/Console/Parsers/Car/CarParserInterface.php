<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\ListInterface;

interface CarParserInterface
{
    /**
     * @param string $string
     * @return ListInterface
     */
    public function parse(string $string): ListInterface;
}
