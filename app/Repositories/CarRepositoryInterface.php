<?php

namespace App\Repositories;

use App\Console\DomainModel\CarInterface;
use App\Console\DomainModel\ListInterface;

interface CarRepositoryInterface
{
    public function saveCarList(ListInterface $list): bool;

    public function checkVin(string $vin): bool;
}
