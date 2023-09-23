<?php

namespace Tests\Feature;

use App\Exceptions\CarRepositoryException;
use App\Repositories\CarRepository;
use App\Repositories\CarRepositoryInterface;
use Mockery\MockInterface;
use Tests\TestCase;

class StringParserCommandTest extends TestCase
{
    /**
     * @return array
     */
    public function dataHandle(): array
    {
        return [
            "Пеередана пустая строка" => [
                'string' => "",
                'isSuccessful' => false
            ],
            "Пеередана некорректная строка" => [
                'string' => "invalid_string",
                'isSuccessful' => false
            ],
            'Передана корректная json строка' => [
                'string' => '{"data":{"cars":[{"vin":"12345678901234567","mark":"random_mark"},{"vin":"12345678906234567","mark":"random_mark"}]}}',
                'isSuccessful' => true,
            ],
            'Передана некорректная json строка' => [
                'string' => '{"data":{"cars":[{"vinfake":"12345678901234567","mark":"random_mark"},{"vinfake":"12345678906234567","mark":"random_mark"}]}}',
                'isSuccessful' => false,
            ],
            'Передана корректная xml строка' => [
                'string' => '<?xml version=\"1.0\" encoding=\"UTF-8\"?><data><cars><car><vin>23f23234234cssdf3</vin><mark>random_mark</mark></car></cars></data>',
                'isSuccessful' => true,
            ],
            'Передана некорректная xml строка' => [
                'string' => '<?xml version="1.0" encoding="UTF-8"?><data><cars><carfake><vin>23f23234234cssdf3</vin><mark>random_mark</mark></carfake></cars></data>',
                'isSuccessful' => false,
            ],
            'Передана корректная json строка, но возникла ошибка при сохранении в БД' => [
                'string' => '{"data":{"cars":[{"vin":"12345678901234567","mark":"random_mark"},{"vin":"12345678906234567","mark":"random_mark"}]}}',
                'isSuccessful' => false,
                'isSaveFailed' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataHandle
     * @param string $string
     * @param bool $isSuccessful
     * @param bool $isSaveFiled
     */
    public function testHandle(string $string, bool $isSuccessful, bool $isSaveFiled = false)
    {
        $this->instance(CarRepositoryInterface::class, \Mockery::mock(CarRepository::class, function (MockInterface $mock) use ($isSaveFiled) {
            $mock->shouldReceive('saveCarList')->andReturnUsing(function () use ($isSaveFiled) {
                switch ($isSaveFiled) {
                    case true:
                        throw new CarRepositoryException("Failed save");
                    default:
                        return true;
                }
            });
        }));

        $command =$this->artisan("string:parse '{$string}'");
        if ($isSuccessful) {
            $command->assertSuccessful();
        } else {
            $command->assertFailed();
        }
    }
}
