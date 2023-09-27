<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\CarList;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarJsonParserException;
use App\Exceptions\CarListException;
use App\Exceptions\CarParserException;

class CarJsonParser extends CarParser
{
    public const ROOT_FIELD = 'data';

    /**
     * парсинг строки формата json и возвращает коллекцию пар vin и mark
     * @param string $string
     * @return ListInterface
     * @throws CarJsonParserException
     * @throws CarParserException
     */
    public function parse(string $string): ListInterface
    {
        $jsonArray = json_decode($string, true);
        if (!is_array($jsonArray)) {
            throw new CarJsonParserException('Wrong json string');
        }

        if (!isset($jsonArray[self::ROOT_FIELD]) || count($jsonArray) > 1) {
            throw new CarJsonParserException("Incorrect JSON string format in level '".self::ROOT_FIELD."' key.");
        }

        $jsonArray = $jsonArray[self::ROOT_FIELD];
        if (!isset($jsonArray[self::LIST_CAR_FIELD]) || count($jsonArray) > 1) {
            throw new CarJsonParserException("Incorrect JSON string format in level '".self::LIST_CAR_FIELD."' key.");
        }

        $jsonArray = $jsonArray[self::LIST_CAR_FIELD];
        $carList = new CarList();

        try {
            foreach ($jsonArray as $index => $carInfo) {
                $car = $this->parseToCar($jsonArray[$index]);
                $carList->add($car);
            }
        } catch (CarListException $exception) {
            throw new CarJsonParserException($exception->getMessage());
        }

        return $carList;
    }
}
