<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Route;
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
        'finder_name' => 'routes.txt',
        'ignoreFirstLine' => true
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csv = $this->parseCSV();
        foreach ($csv as $row) {
            $route = new Route();
            $route->setId($row[0] ?? null);
            $route->setShortName($row[1] ?? null);
            $route->setLongName($row[2] ?? null);
            $route->setDescription($row[3] ?? null);
            $route->setType($row[4] ?? null);

            $this->entityManager->persist($route);
         
            // dump($row); // Assurez-vous que dump() est configuré correctement
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function parseCSV(): array
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name($this->csvParsingOptions['finder_name']);
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
