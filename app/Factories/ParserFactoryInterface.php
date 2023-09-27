<?php

namespace App\Factories;

use App\Console\Parsers\Car\CarParser;
//use App\Console\Parsers\Car\CarParserInterface;
//use App\Exceptions\CreateCarParserException;

interface ParserFactoryInterface
{
    /**
     * @param string $str
     * @return CarParser
     */
    public function createCarParser(string $str): CarParser;
}
