<?php

namespace App\Repositories;

use App\Console\DomainModel\CarInterface;
use App\Console\DomainModel\ListInterface;
use App\Exceptions\CarRepositoryException;
use App\Models\Car;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;

class CarRepository implements CarRepositoryInterface
{
    /**
     * @var ConnectionInterface
     */
    protected ConnectionInterface $connection;

    /**
     * CarRepository constructor.
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection) {
        $this->connection = $connection;
    }

    /**
     * Сохранение коллекции пар vin и mark
     * @param ListInterface $carList
     * @return bool
     * @throws CarRepositoryException
     */
    public function saveCarList(ListInterface $carList): bool
    {
        $this->connection->beginTransaction();
        foreach ($carList->all() as $car) {
            try {
                $this->saveCar($car);
            }  catch (QueryException $e) {
                $this->connection->rollBack();
                throw new CarRepositoryException("In save collection process throw error with code - ".$e->getCode());
            }
        }
        $this->connection->commit();
        return true;
    }

    /**
     * Сохраняет пару vin и mark
     * @param CarInterface $car
     * @return bool
     */
    protected function saveCar(CarInterface $car): bool
    {
        $carModel = new Car();
        $carModel->setVin($car->getVin());
        $carModel->setMark($car->getMark());
        return $carModel->save();
    }

}
