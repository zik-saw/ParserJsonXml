<?php

namespace App\Console\DomainModel;

use App\Exceptions\CarListException;

class CarList implements ListInterface
{
    /**
     * @var array<int, CarInterface>
     */
    private array $list = [];

    /**
     * CarList constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param CarInterface $car
     * @throws CarListException
     */
    public function add(CarInterface $car): void
    {
        if (!$this->isExistVinInList($car->getVin())) {
            throw new CarListException("`vin` {$car->getVin()} not unique in parsed string.");
        }
        $this->list[] = $car;
    }

    /**
     * @param string $vin
     * @return bool
     */
    protected function isExistVinInList(string $vin) : bool
    {
        /** @var CarInterface $car */
        foreach ($this->all() as $car) {
            if ($car->getVin() === $vin) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array<int, CarInterface>
     */
    public function all(): array
    {
        return $this->list;
    }
}
