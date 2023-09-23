<?php

namespace Tests\Feature;

use App\Exceptions\CarParserException;
use Tests\TestCase;
use App\Console\Parsers\Car\CarParser;
use Mockery;
use App\Repositories\CarRepositoryInterface;
use App\Repositories\CarRepository;

class CarParserTest extends TestCase
{
    /**
     * @return array
     */
    public function dataValidateCarFields(): array
    {
        return [
            'В массиве передан лишний ключ' => [
                'carInfo' => [
                    'vin' => 'vin',
                    'mark' => 'mark',
                    'fake' => 'fake',
                ],
                "exceptionMessage" => 'Incorrect string format - in filed `car` count fields must be 2',
            ],
            'В массиве отсуствует ключ '.CarParser::CAR_FIELD_VIN => [
                'carInfo' => [
                    'mark' => 'mark',
                    'fake1' => 'fake1',
                ],
                "exceptionMessage" => "Incorrect string format - in car missing  '".CarParser::CAR_FIELD_VIN."' key",
            ],
            'В массиве отсуствует ключ '.CarParser::CAR_FIELD_MARK => [
                'carInfo' => [
                    'vin' => 'vin',
                    'fake1' => 'fake1',
                ],
                "exceptionMessage" => "Incorrect string format - in car missing '".CarParser::CAR_FIELD_MARK."' key",
            ],
            'Некорректное кол-во символов у поля '.CarParser::CAR_FIELD_VIN => [
                'carInfo' => [
                    'vin' => 'vin',
                    'mark' => 'mark',
                ],
                "exceptionMessage" => "Incorrect length ".CarParser::CAR_FIELD_VIN,
            ],
            'Проверка, что бросается ошибка, если метод проверки существовавния vin вернул false' => [
                'carInfo' => [
                    'vin' => 'correct_vin_12345',
                    'mark' => 'mark',
                ],
                "exceptionMessage" => 'Same vin is exist in DB - correct_vin_12345',
                'checkVinExpected' => true,
            ],
            'Проверка, что метод успешно прошел все проверки и вернул true' => [
                'carInfo' => [
                    'vin' => 'correct_vin_12345',
                    'mark' => 'mark',
                ],
                "exceptionMessage" => '',
            ],

        ];
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @dataProvider dataValidateCarFields
     * @param array $carInfo
     * @param string $exceptionMessage
     * @param bool $checkVinExpected
     * @throws CarParserException
     */
    public function testValidateCarFields($carInfo = [], $exceptionMessage = "", $checkVinExpected = false)
    {
        if ($exceptionMessage) {
            $this->expectException(CarParserException::class);
            $this->expectErrorMessage($exceptionMessage);
        }

        $repositoryMock = Mockery::mock('overload:'.CarRepository::class, CarRepositoryInterface::class);
        $repositoryMock->shouldReceive('checkVin')->andReturn($checkVinExpected);


        $stub = $this->getMockForAbstractClass(CarParser::class, ["stubString"]);
        $this->assertTrue($stub->validateCarFields($carInfo));
    }
}
