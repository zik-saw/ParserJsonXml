<?php

namespace App\Factories;

use App\Console\Parsers\Car\CarJsonParser;
use App\Console\Parsers\Car\CarParserInterface;
use App\Console\Parsers\Car\CarXmlParser;
use App\Services\Helpers;
use App\Exceptions\CreateCarParserException;

class ParserFactory implements ParserFactoryInterface
{
    private Helpers $helpers ;
    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Фабрика, которая проверяет, что тип строки соотвествуюет поддрживающего формата и возвращает соотвествующий парсер
     * @param string $str
     * @return CarParserInterface
     * @throws CreateCarParserException
     */
    public function createCarParser(string $str): CarParserInterface
    {
        if ($this->helpers->isJson($str)) {
            return new CarJsonParser($str);
        } elseif ($this->helpers->isXml($str)) {
            return new CarXmlParser($str);
        } else {
            throw new CreateCarParserException('Invalid string type format');
        }
    }
}
