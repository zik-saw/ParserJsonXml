<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\ListInterface;

interface CarParserInterface
{
    /**
     * @return ListInterface
     */
    public function parse(): ListInterface;
}
