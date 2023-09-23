<?php

namespace App\Factories;

use App\Console\Parsers\Car\CarParserInterface;
use App\Exceptions\CreateCarParserException;

interface ParserFactoryInterface
{
    /**
     * @param string $str
     * @return CarParserInterface
     * @throws CreateCarParserException
     */
    public function createCarParser(string $str): CarParserInterface;
}
