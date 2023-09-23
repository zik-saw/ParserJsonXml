<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\CarInterface;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarParserException;
use App\Repositories\CarRepository;
use App\Repositories\CarRepositoryInterface;
use App\Models\Car as CarModel;
use App\Console\DomainModel\Car as CarDomain;

abstract class CarParser implements CarParserInterface
{
    public const CAR_FIELD_VIN = 'vin';
    public const CAR_FIELD_MARK = 'mark';
    public const LIST_CAR_FIELD = 'cars';

    protected string $str = "";
    protected CarRepositoryInterface $carRepository;


    /**
     * CarParser constructor.
     * @param string $str
     */
    public function __construct(string $str)
    {
        $this->str = $str;
        $this->carRepository = new CarRepository();
    }

    /**
     * проверяет, что в массиве присуттсвуют необходимые ключи vin и mark
     * @param array<string,string> $data
     * @return bool
     * @throws CarParserException
     */
    public function validateCarFields(array $data = []): bool
    {
        if (count($data) > 2) {
            throw new CarParserException('Incorrect string format - in filed `car` count fields must be 2');
        }
        if (empty($data[self::CAR_FIELD_MARK])) {
            throw new CarParserException("Incorrect string format - in car missing '".self::CAR_FIELD_MARK."' key");
        }
        if (empty($data[self::CAR_FIELD_VIN])) {
            throw new CarParserException("Incorrect string format - in car missing  '".self::CAR_FIELD_VIN."' key");
        }

        if (CarModel::VIM_FIELD_VALUE_LENGTH !== strlen($data[self::CAR_FIELD_VIN])) {
            throw new CarParserException("Incorrect length ".self::CAR_FIELD_VIN);
        }

        if ($this->carRepository->checkVin($data[self::CAR_FIELD_VIN])) {
            throw new CarParserException('Same vin is exist in DB - '.$data[self::CAR_FIELD_VIN]);
        }

        return true;
    }

    /**
     * возвращает объъкт, в котором хранятся пары vin и mark
     * @param array<string,string> $carInfo
     * @return CarInterface
     * @throws CarParserException
     */
    public function parseToCar(array $carInfo = []): CarInterface
    {
        $this->validateCarFields($carInfo);
        return new CarDomain(
            $carInfo[self::CAR_FIELD_VIN],
            $carInfo[self::CAR_FIELD_MARK]
        );
    }

    abstract public function parse(): ListInterface;
}
