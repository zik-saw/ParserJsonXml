<?php

namespace Tests\Feature;

use App\Exceptions\CarParserException;
use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Console\Parsers\Car\CarParser;
use Mockery;
use App\Repositories\CarRepositoryInterface;
use App\Repositories\CarRepository;

class CarParserTest extends TestCase
{
    use RefreshDatabase;
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
                "exceptionMessage" => "vin - Обязательное поле для парсинга  Исходные данные - {\"mark\":\"mark\",\"fake1\":\"fake1\"}",
            ],
            'В массиве отсуствует ключ '.CarParser::CAR_FIELD_MARK => [
                'carInfo' => [
                    'vin' => 'dsds324scas333bs',
                    'fake1' => 'fake1',
                ],
                "exceptionMessage" => "mark - Обязательное поле для парсинга  Исходные данные - {\"vin\":\"dsds324scas333bs\",\"fake1\":\"fake1\"}",
            ],
            'Некорректное кол-во символов у поля '.CarParser::CAR_FIELD_VIN => [
                'carInfo' => [
                    'vin' => 'dsds324sdas33',
                    'mark' => 'mark',
                ],
                "exceptionMessage" => "vin - Кол-во символово должно быть 16  Исходные данные - {\"vin\":\"dsds324sdas33\",\"mark\":\"mark\"}",
            ],
            'Возвращаетсся предупреждение, что такой vin уже есть в БД' => [
                'carInfo' => [
                    'vin' => 'existed_vin_1918',
                    'mark' => 'mark',
                ],
                "exceptionMessage" => 'vin - Поле с таким значением уже создано  Исходные данные - {"vin":"existed_vin_1918","mark":"mark"}',
                'checkVinExpected' => true,
            ],
            'Валидация прошла успешно' => [
                'carInfo' => [
                    'vin' => 'correct_vin_1234',
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
    public function testValidateCarFields($carInfo = [], $exceptionMessage = "" )
    {
        Car::factory()->create([
            'vin' => 'existed_vin_1918'
        ]);

        if ($exceptionMessage) {
            $this->expectException(CarParserException::class);
            $this->expectErrorMessage($exceptionMessage);
        }

        $stub = $this->getMockForAbstractClass(CarParser::class);
        $this->assertTrue($stub->validateCarFields($carInfo));
    }
}
