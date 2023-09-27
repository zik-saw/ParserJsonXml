<?php

namespace App\Console\Parsers\Car;

use App\Console\DomainModel\CarInterface;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarParserException;
use App\Models\Car as CarModel;
use App\Console\DomainModel\Car as CarDomain;
use Illuminate\Support\Facades\Validator;

abstract class CarParser
{
    public const CAR_FIELD_VIN = 'vin';
    public const CAR_FIELD_MARK = 'mark';
    public const LIST_CAR_FIELD = 'cars';


    /**
     * @return array<string, array<int,string>>
     */
    protected function getValidationRules(): array {
        return [
            self::CAR_FIELD_MARK => [
                'required', 'string',
            ],
            self::CAR_FIELD_VIN => [
              'required', 'unique:App\Models\Car', 'min:16', 'max:16'
            ],
        ];
    }

    /**
     * @return array<string,string>
     */
    protected function getValidationMessages(): array {
        return [
            'required' => "Обязательное поле для парсинга",
            'string' => "Поле должено быть строкой",
            'unique' => "Поле с таким значением уже создано",
            'min' => "Кол-во символово должно быть 16",
            "max" => "Кол-во символово должно быть 16",
        ];
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

        $validator = Validator::make($data, $this->getValidationRules(), $this->getValidationMessages());
        if($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                $errorMessage .= "{$field} - ".implode(", ", $messages)." ";
            }
            throw new CarParserException($errorMessage." Исходные данные - ".json_encode($data, true));
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

    abstract public function parse(string $string): ListInterface;
}
