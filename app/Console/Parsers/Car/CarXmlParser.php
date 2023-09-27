<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\CarList;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarListException;
use App\Exceptions\CarParserException;
use App\Exceptions\CarXmlParserException;

class CarXmlParser extends CarParser
{
    public const CAR_INDEX_FIELD = 'car';

    /**
     * парсинг строки формата xml и возвращает коллекцию пар vin и mark
     * @param string $string
     * @return ListInterface
     * @throws CarParserException
     * @throws CarXmlParserException
     */
    public function parse(string $string): ListInterface
    {
        $xml = @simplexml_load_string($string, \SimpleXMLElement::class, LIBXML_NOCDATA);

        if (!$xml) {
            throw new CarXmlParserException("Incorrect XML string");
        }

        if (!isset($xml->{CarParser::LIST_CAR_FIELD}) || $xml->count() > 1) {
            throw new CarXmlParserException("Incorrect XML format in level '".self::LIST_CAR_FIELD."' node.");
        }

        $xml = $xml->{CarParser::LIST_CAR_FIELD};
        if (!isset($xml->{self::CAR_INDEX_FIELD}) || $xml->children()->count() != $xml->{self::CAR_INDEX_FIELD}->count()) {
            throw new CarXmlParserException("Incorrect XML format in level '".self::CAR_INDEX_FIELD."' node.");
        }

        $carList = new CarList();
        $index = 0;
        try {
            while ($carInfo = (array)$xml->{self::CAR_INDEX_FIELD}[$index++]) {
                $car = $this->parseToCar($carInfo);
                $carList->add($car);
            }
        } catch (CarListException $exception) {
            throw new CarXmlParserException($exception->getMessage());
        }

        return $carList;
    }
}
