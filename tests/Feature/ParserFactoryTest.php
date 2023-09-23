<?php

namespace Tests\Feature;

use App\Console\Parsers\Car\CarJsonParser;
use App\Console\Parsers\Car\CarParser;
use App\Console\Parsers\Car\CarParserInterface;
use App\Console\Parsers\Car\CarXmlParser;
use App\Exceptions\CreateCarParserException;
use App\Factories\ParserFactory;
use App\Services\Helpers;
use Tests\TestCase;

class ParserFactoryTest extends TestCase
{
    /**
     * @return array
     */
    public function dataCreateCarParser(): array
    {
        return [
            "Фабрика вернула объект типа - ".CarJsonParser::class => [
                'expectedClass' => CarJsonParser::class,
            ],
            "Фабрика вернула объект типа - ".CarXmlParser::class => [
                'expectedClass' => CarXmlParser::class,
            ],
            "Неизвестный тип строки, фабрика вернула ошибку" => [
                'expectedValue' => "",
                'expectedErrorMessage' =>'Invalid string type format'
            ],
        ];
    }

    /**
     * @dataProvider dataCreateCarParser
     * @param string $expectedClass
     * @param string $expectedErrorMessage
     * @throws CreateCarParserException
     */
    public function testCreateCarParser(string $expectedClass, string $expectedErrorMessage = "")
    {
        if ($expectedErrorMessage) {
            $this->expectException(CreateCarParserException::class);
            $this->expectErrorMessage($expectedErrorMessage);
        }

        $mock = $this->getMockBuilder(Helpers::class)->onlyMethods(['isJson','isXml'])->getMock();


        $mock->expects($this->any())->method('isJson')->willReturn($expectedClass == CarJsonParser::class ? true : false);
        $mock->expects($this->any())->method('isXml')->willReturn($expectedClass == CarXmlParser::class ? true : false);

        $factory = new ParserFactory($mock);
        $object = $factory->createCarParser("");
        $this->assertEquals(get_class($object), $expectedClass);
    }
}
