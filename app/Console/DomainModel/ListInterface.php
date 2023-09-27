<?php

namespace App\Console\DomainModel;

interface ListInterface
{
    /**
     * @param Car $car
     */
    public function add(Car $car): void;

    /**
     * @return array<string, CarInterface>
     */
    public function all(): array;
}
