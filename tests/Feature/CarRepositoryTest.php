<?php

namespace Tests\Feature;

use App\Console\DomainModel\CarList;
use App\Exceptions\CarRepositoryException;
use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array
     */
    public function dataCheckVin(): array
    {
        return [
            'vin существует в БД' => [
                'newVin' => "23f23234234cssdfn",
                'vinInDB' => '23f23234234cssdf3',
                'expectedValue' => false,
            ],
            'vin не существует в БД' => [
                'newVin' => "23f23234234cssdfn",
                'vinInDB' => '23f23234234cssdfn',
                'expectedValue' => true,
            ]
        ];
    }

    /**
     * @dataProvider dataCheckVin
     * @param string $newVin
     * @param string $vinInDB
     * @param bool $expectedValue
     */
    public function testCheckVin(string $newVin, string $vinInDB, bool $expectedValue)
    {
        $cars =  Car::factory()->create([
          'vin' => $vinInDB
        ]);

        $repository = new CarRepository();
        $this->assertEquals($expectedValue, $repository->checkVin($newVin));
    }


    /**
     * @return array
     */
    public function dataSaveCarList()
    {
        return [
            'В пустую таблицу успешно добавился новвая пара vin и mark' => [
                'carInfoList' =>[
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf3'
                    ],
                ],
                'countInTable' => 1,
            ],
            'В БД успешно добавилась коллекция пар vin и mark' => [
                'carInfoList' =>[
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf3'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf6'
                    ],
                ],
                'countInTable' => 4,
                'vinInDB' => [
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234bb4cssdf3'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b3mcssdf6'
                    ],
                ],
            ],
            'В БД не добавилась новый элмент, поскольку vin уже есть в БД' => [
                'carInfoList' =>[
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf3'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf6'
                    ],
                ],
                'countInTable' => 2,
                'vinInDB' => [
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf6'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b3mcssdf6'
                    ],
                ],
                'expectedException' => true
            ],
            'В БД не добавились новые элементы, поскольку  в коллекции есть пары, у которых vin совпадают' => [
                'carInfoList' =>[
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf3'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf6'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b34cssdf6'
                    ],
                ],
                'countInTable' => 2,
                'vinInDB' => [
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234bb4cssdf3'
                    ],
                    [
                        'mark' => 'some_mark',
                        'vin' => '23223234b3mcssdf6'
                    ],
                ],
                'expectedException' => true
            ],
        ];
    }

    /**
     * @dataProvider dataSaveCarList
     * @param iterable $carInfoList
     * @param int $countInTable
     * @param array $vinInDB
     * @param bool $expectedException
     * @throws CarRepositoryException
     */
    public function testSaveCarList(iterable $carInfoList, int $countInTable, $vinInDB = [], bool $expectedException = false)
    {
        if ($expectedException) {
            $this->expectException(CarRepositoryException::class);
        }
        $carRepositoryException = null;
        $cars = [];
        foreach ($carInfoList as $carInfo) {
            $cars[] = new \App\Console\DomainModel\Car($carInfo['vin'], $carInfo['mark']);
        }

        if (!empty($vinInDB)) {
            Car::factory()->createMany($vinInDB);
        }

        if ($expectedException) {
            $this->expectException(CarRepositoryException::class);
        }

        $mock = $this->getMockBuilder(CarList::class)->onlyMethods(['all'])->getMock();
        $mock->expects($this->once())->method('all')->willReturn($cars);


        $carRepository = new CarRepository();
        try {
            $carRepository->saveCarList($mock);
            foreach ($carInfoList as $carInfo) {
                $this->assertDatabaseHas('cars', [
                    'vin' => $carInfo['vin']
                ]);
            }
        } catch (CarRepositoryException $exception) {
            $carRepositoryException = $exception;
        } finally {
            $this->assertDatabaseCount('cars', $countInTable);
            if ($carRepositoryException) {
                throw $carRepositoryException;
            }
        }
    }
}
