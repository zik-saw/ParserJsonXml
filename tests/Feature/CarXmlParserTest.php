<?php

namespace Tests\Feature;

use App\Console\Parsers\Car\CarXmlParser;
use App\Exceptions\CarXmlParserException;
use Tests\TestCase;

class CarXmlParserTest extends TestCase
{
    /**
     * @return array
     */
    public function dataParse(): array
    {
        return [
            'Некорректный формат строки, вернулась ошибка' => [
                'str' => "null",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => 'Incorrect XML string'
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне узла `'.CarXmlParser::LIST_CAR_FIELD.'`, отсутсвует узел - '.CarXmlParser::LIST_CAR_FIELD => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car><car><vin>23f232342b4cssdf3</vin><mark>random_mark</mark></car></cars>",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => "Incorrect XML format in level '".CarXmlParser::LIST_CAR_FIELD."' node."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне узла `'.CarXmlParser::LIST_CAR_FIELD.'`, на уровне узла есть лишние элементы' => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car></cars><carsfake><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car></carsfake></data>",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => "Incorrect XML format in level '".CarXmlParser::LIST_CAR_FIELD."' node."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarXmlParser::CAR_INDEX_FIELD.'`, на уровне узла есть лишние элементы' => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car><carfake><vin>23f23234234cssdf3</vin><mark>random_mark</mark></carfake></cars></data>",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => "Incorrect XML format in level '".CarXmlParser::CAR_INDEX_FIELD."' node."
                ],
                'collectionLength' => 0,
            ],
            'Некорректный формат строки на уровне ключа `'.CarXmlParser::CAR_INDEX_FIELD.'`,  отсутсвует узел - '.CarXmlParser::CAR_INDEX_FIELD => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><carfake><vin>23f23234234cssdf3</vin><mark>random_mark</mark></carfake></cars></data>",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => "Incorrect XML format in level '".CarXmlParser::CAR_INDEX_FIELD."' node."
                ],
                'collectionLength' => 0,
            ],
            'В строке есть свопадающие vin' => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car><car><vin>23f23234234c5sdf3</vin><mark>random_mark</mark></car><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car></cars></data>",
                'expectedExceptionData' => [
                    'class' => CarXmlParserException::class,
                    'messageError' => "`vin` 23f23234234cssdf3 not unique in parsed string."
                ],
                'collectionLength' => 0,
            ],
            'Строка корректно спарсилась, вернулась коллекция с 1 объектом' => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car></cars></data>",
                'expectedException' => [],
                'collectionLength' => 1,
            ],
            'Строка корректно спарсилась, вернулась коллекция с 3 объектом' => [
                'str' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car><car><vin>23f23234234c5sdf3</vin><mark>random_mark</mark></car><car><vin>23f232342bncssdf3</vin><mark>random_mark</mark></car></cars></data>",
                'expectedException' => [],
                'collectionLength' =>3,
            ],
        ];
    }

    /**
     * @dataProvider dataParse
     * @param string $xmString
     * @param iterable $expectedException
     * @param int $collectionLength
     */
    public function testParseMethod(string $xmString, iterable $expectedException, int $collectionLength)
    {
        if (!empty($expectedException)) {
            $this->expectException($expectedException['class']);
            $this->expectErrorMessage($expectedException['messageError']);
        }

        $mock = $this->getMockBuilder(CarXmlParser::class)->onlyMethods(['validateCarFields'])->getMock();
        $mock->expects($this->any())->method('validateCarFields');

        $collection = $mock->parse($xmString);

        $this->assertEquals(count($collection->all()), $collectionLength);
    }
}
