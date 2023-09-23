<?php

namespace App\Repositories;

use App\Console\DomainModel\CarInterface;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarRepositoryException;
use App\Models\Car;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CarRepository implements CarRepositoryInterface
{
    /**
     * Сохранение коллекции пар vin и mark
     * @param ListInterface $carList
     * @return bool
     * @throws CarRepositoryException
     */
    public function saveCarList(ListInterface $carList): bool
    {
        $connection = DB::connection();
        try {
            $connection->beginTransaction();

            foreach ($carList->all() as $car) {
                $this->saveCar($car);
            }

            $connection->commit();
            return true;
        } catch (QueryException $e) {
            $connection->rollBack();
            throw new CarRepositoryException("При сохранении коллекции произоошла ошибка с кодом - ".$e->getCode());
        }
    }

    /**
     * Сохраняет пару vin и marl
     * @param CarInterface $car
     * @return bool
     */
    protected function saveCar(CarInterface $car): bool
    {
        $carModel = Car::create([
            'vin' => $car->getVin(),
            'mark' => $car->getMark(),
        ]);
        $carModel->save();
        return true;
    }

    /**
     * Проверяет, что vin нет в таблице
     * @param string $vin
     * @return bool
     */
    public function checkVin(string $vin): bool
    {
        return Car::where('vin', $vin)->count() ? true : false;
    }
}
