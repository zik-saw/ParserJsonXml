<?php

namespace Tests\Feature;

use App\Services\Helpers;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @return array
     */
    public function dataIsJson(): array
    {
        return [
            "Если пустая строка" => [
                'string' => '',
                'expectedValue' => false,
            ],
            "Если строка с набром случайных символов" => [
                'string' => '',
                'expectedValue' => false,
            ],
            "Если строка с json строкой, у которой отсуствуют необходимые скобки" => [
                'string' => '{"data":ars":[{"vin":"23223234b34cssdf3","mark":"sds"},{"vin":"23223234b34cssdf3","mark":"sds"},{"vin":"23223234b34cssdf3","mark":"sds"}]',
                'expectedValue' => false,
            ],
            "Корректная json строка" => [
                'string' => '{"data":{"cars":[{"vin":"random_vin","mark":"random_mark"}]}}',
                'expectedValue' => true,
            ]
        ];
    }

    /**
     * @dataProvider dataIsJson
     * @param string $string
     * @param bool $expectedValue
     */
    public function testIsJson(string $string, bool $expectedValue)
    {
        $helpers = new Helpers();
        self::assertEquals($helpers->isJson($string), $expectedValue);
    }

    /**
     * @return array
     */
    public function dataIsXml(): array
    {
        return [
            "Если пустая строка" => [
                'string' => '',
                'expectedValue' => false,
            ],
            "У строки отсуствует xml заголовок" => [
                'string' => '<data><cars><car><vin>random_vin</vin><mark>random_mark</mark></car></cars></data>',
                'expectedValue' => true,
            ],
            "В строке хранится некорректный xml, у которого отсуствует закрывающие или открывающие теги" => [
                'string' => '<?xml version="1.0" encoding="UTF-8"?><data><vin>random_vin</vin><mark>random_mark</mark></car></cars></data>',
                'expectedValue' => false,
            ],
            "В строке хранится некорректный xml, у которого на одном уровне отличаются наименования открываюищ и закрывающих тегов" => [
                'string' => '<?xml version="1.0" encoding="UTF-8"?><data><cars1><car2><vin>random_vin</vin><mark>random_mark</mark></car></cars></data>',
                'expectedValue' => false,
            ],
            "В строке хранится корректный xml" => [
                'string' => '<?xml version="1.0" encoding="UTF-8"?><data><cars><car><vin>random_vin</vin><mark>random_mark</mark></car></cars></data>',
                'expectedValue' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataIsXml
     * @param string $string
     * @param bool $expectedValue\
     */
    public function testIsXml(string $string, bool $expectedValue)
    {
        $helpers = new Helpers();
        self::assertEquals($helpers->isXml($string), $expectedValue);
    }
}
