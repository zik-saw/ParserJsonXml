<?php

namespace App\Console\DomainModel;

use App\Exceptions\CarListException;

class CarList implements ListInterface
{
    /**
     * @var array<string, CarInterface>
     */
    private array $list = [];

    /**
     * @param CarInterface $car
     * @throws CarListException
     */
    public function add(CarInterface $car): void
    {
        if(isset($this->list[$car->getVin()])) {
            throw new CarListException("`vin` {$car->getVin()} not unique in parsed string.");
        }
        $this->list[$car->getVin()] = $car;
    }
    /**
     * @return array<string, CarInterface>
     */
    public function all(): array
    {
        return $this->list;
    }
}
