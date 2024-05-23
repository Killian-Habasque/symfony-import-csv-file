<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Route;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:DataimportCommand',
    description: 'Add a short description for your command',
)]
class DataimportCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private array $csvParsingOptions = [
        'finder_in' => 'public/data',
        'ignoreFirstLine' => true
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csv = $this->parseCSV('routes.txt');
        foreach ($csv as $row) {
            $route = new Route();
            $route->setId($row[0] ?? null);
            $route->setShortName($row[1] ?? null);
            $route->setLongName($row[2] ?? null);
            $route->setDescription($row[3] ?? null);
            $route->setType($row[4] ?? null);

            $this->entityManager->persist($route);
         
            // dump($row);
        }
        $this->entityManager->flush();

        $csv = $this->parseCSV('trips.txt');
        $i = 0;
        foreach ($csv as $row) {
            $i++;
            $trip = new Trip();
            $trip->setId($row[2] ?? null);
            $trip->setHeadsign($row[3] ?? null);
            $trip->setDescriptionId($row[4] ?? null);
            $trip->setBlockId($row[5] ?? null);
            $route = $this->entityManager->getRepository(Route::class)->find($row[0] ?? null);
            $trip->setRoute($route);
            $this->entityManager->persist($trip);

            if($i == 1000) {
                $i = 0;
                $this->entityManager->flush();
            }
            // dump($row);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function parseCSV($finder_name): array
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($finder_name);
        foreach ($finder as $file) {
            $csv = $file;
        }

        $rows = [];
        if (($handle = fopen($csv->getRealPath(), "r")) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ",")) !== false) {
                $i++;
                if ($ignoreFirstLine && $i === 1) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
