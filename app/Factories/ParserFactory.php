<?php

namespace App\Factories;

use App\Console\Parsers\Car\CarJsonParser;
use App\Console\Parsers\Car\CarParser;
use App\Console\Parsers\Car\CarParserInterface;
use App\Console\Parsers\Car\CarXmlParser;
use App\Services\Helpers;
use App\Exceptions\CreateCarParserException;

class ParserFactory implements ParserFactoryInterface
{
    private Helpers $helpers ;

    /**
     * ParserFactory constructor.
     * @param Helpers $helpers
     */
    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Фабрика, которая провеярет тип строки и возвращает соотвествующий парсер
     * @param string $str
     * @return CarParser
     * @throws CreateCarParserException
     */
    public function createCarParser(string $str): CarParser
    {
        if ($this->helpers->isJson($str)) {
            return new CarJsonParser();
        } elseif ($this->helpers->isXml($str)) {
            return new CarXmlParser();
        } else {
            throw new CreateCarParserException('Invalid string type format');
        }
    }
}
