<?php

namespace App\Console\DomainModel;

interface ListInterface
{
    /**
     * @param Car $car
     */
    public function add(Car $car): void;

    /**
     * @return array<int, CarInterface>
     */
    public function all(): array;
}
