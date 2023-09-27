<?php

namespace Tests\Feature;

use App\Console\Parsers\Car\CarJsonParser;
use App\Exceptions\CarJsonParserException;
use Tests\TestCase;

class CarJsonParserTest extends TestCase
{
    /**
     * @return array
     */
    public function dataParse(): array
    {
        return [
            'Строка корректно спарсилась, вернулась коллекция с 1 объектом' => [
                'str' => "{\"data\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedException' => [],
                'collectionLength' => 1,
            ],
            'Если пустая строка, то возращается ошибка' => [
                'str' => "",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => 'Wrong json string'
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarJsonParser::ROOT_FIELD.'`, на этом уровне есть лишний ключ' => [
                'str' => "{\"data\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]},\"dataFake\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => "Incorrect JSON string format in level '".CarJsonParser::ROOT_FIELD."' key."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarJsonParser::ROOT_FIELD.'`, на этом уровне отсутсвует ключ - '.CarJsonParser::ROOT_FIELD => [
                'str' => "{\"dataFake\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => "Incorrect JSON string format in level '".CarJsonParser::ROOT_FIELD."' key."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarJsonParser::LIST_CAR_FIELD.'`, на этом уровне есть лишний ключ' => [
                'str' => "{\"data\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}],\"carsFake\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => "Incorrect JSON string format in level '".CarJsonParser::LIST_CAR_FIELD."' key."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarJsonParser::LIST_CAR_FIELD.'`, на этом уровне отсутсвует ключ - '.CarJsonParser::LIST_CAR_FIELD => [
                'str' => "{\"data\":{\"carsFake\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => "Incorrect JSON string format in level '".CarJsonParser::LIST_CAR_FIELD."' key."
                ],
                'collectionLength' => 0,
            ],
            'Строка корректно спарсилась, вернулась коллекция с 3 объектом' => [
                'str' => "{\"data\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"},{\"vin\":\"23223234b34cssdb3\",\"mark\":\"sds\"},{\"vin\":\"23223234434csszf3\",\"mark\":\"sds\"}]}}",
                'expectedException' => [],
                'collectionLength' => 3,
            ],
            'В строке есть свопадающие vin' => [
                'str' => "{\"data\":{\"cars\":[{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"},{\"vin\":\"23223234b34cssdb3\",\"mark\":\"sds\"},{\"vin\":\"23223234b34cssdf3\",\"mark\":\"sds\"}]}}",
                'expectedExceptionData' => [
                    'class' => CarJsonParserException::class,
                    'messageError' => "`vin` 23223234b34cssdf3 not unique in parsed string."
                ],
                'collectionLength' => 0,
            ],
        ];
    }

    /**
     * @dataProvider dataParse
     * @param string $jsonString
     * @param iterable $expectedException
     * @param int $collectionLength
     */
    public function testParseMethod(string $jsonString, iterable $expectedException, int $collectionLength)
    {
        if (!empty($expectedException)) {
            $this->expectException($expectedException['class']);
            $this->expectErrorMessage($expectedException['messageError']);
        }

        $mock = $this->getMockBuilder(CarJsonParser::class)->onlyMethods(['validateCarFields'])->getMock();
        $mock->expects($this->any())->method('validateCarFields');

        $collection = $mock->parse($jsonString);

        $this->assertEquals(count($collection->all()), $collectionLength);
    }
}
