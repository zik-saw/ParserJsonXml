<?php

namespace App\Console\Commands;

use App\Factories\ParserFactoryInterface;
use App\Repositories\CarRepositoryInterface;
use Illuminate\Console\Command;
use Exception;

class StringParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'string:parse {raw_data : XML or JSON string format }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command parse JSON and XML string';


    /**
     * Команда для считывания пар vin и mark из строки формата json или xml, и сохранение этих пар в таблицу cars
     * @param ParserFactoryInterface $parserFactory
     * @param CarRepositoryInterface $carRepository
     * @return int
     */
    public function handle(ParserFactoryInterface $parserFactory, CarRepositoryInterface $carRepository)
    {
        try {
            $rawData = $this->argument('raw_data');
            if (empty($rawData)) {
                throw new Exception("Cannot parse an empty string.");
            }
            $parser = $parserFactory->createCarParser($rawData);
            $carList = $parser->parse($rawData);
            $carRepository->saveCarList($carList);
            $this->info('Parse string successful!');
            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }
}
