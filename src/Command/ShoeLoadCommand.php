<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Shoe;
use App\Repository\ShoeRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShoeLoadCommand extends Command
{
    protected static $defaultName = 'app:load-shoes';

    private ShoeRepository $shoeRepository;

    private array $shoeCodes = [];
    private string $sheetName = '';
    private int $sheetRowNumber = 0;

    private bool $updateShoe = false;

    public function __construct(ShoeRepository $shoeRepository)
    {
        parent::__construct();

        $this->shoeRepository = $shoeRepository;
    }
    private function flush() : void
    {
        $this->shoeRepository->getEntityManager()->flush();
        $this->shoeCodes = [];
    }
    private function findShoe(string $code) : ?Shoe
    {
        if (isset( $this->shoeCodes[$code])) {
            return $this->shoeCodes[$code];
        }

        //echo $code . "\n";
        $shoe = $this->shoeRepository->findOneByCode($code);
        if ($shoe !== null) {
            $this->shoeCodes[$code] = $shoe;
        }
        return $shoe;
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        // Empty tables
        $conn = $this->shoeRepository->getEntityManager()->getConnection();
        $conn->beginTransaction();
        $conn->executeUpdate('TRUNCATE shoes;');
        $conn->commit();

        // Master list
        $inputFileName = '//home/ahundiak/Downloads/shoes/NikeShoes20200627a.xlsx';
        $ss = IOFactory::load($inputFileName);
        $this->updateShoe = true; // Don't really need this as long as starting from stratch
        $this->processSheet($ss,'Code');
        $this->updateShoe = false;

        //if (true) return Command::SUCCESS;

        // Individual shoe store entries
        $inputFileName = '//home/ahundiak/Downloads/shoes/NikeShoeStores20200627a.xlsx';
        $ss = IOFactory::load($inputFileName);

        //dump($ss->getSheetNames()); die();
        //dump($sheetIndex);

        $this->processSheet($ss,'Celb');
        $this->processSheet($ss,'WG');
        $this->processSheet($ss,'INT');
        $this->processSheet($ss,'INT97');
        $this->processSheet($ss,'Vine');
        $this->processSheet($ss,'Vine97');
        $this->processSheet($ss,'LBV97');
        $this->processSheet($ss,'Celb 97');
        $this->processSheet($ss,'wg 97');

        return Command::SUCCESS;
    }
    private function processSheet(Spreadsheet $ss, string $sheetName) : void
    {
        $this->sheetName = $sheetName;

        $sheetNames = $ss->getSheetNames();
        $sheetIndex = array_search($sheetName,$sheetNames); //die($sheetName . ' ' . $sheetIndex);
        if ($sheetIndex === false) return;

        $rows = $ss->getSheet($sheetIndex)->toArray();
        $this->processRows($rows);

        $this->flush();
    }
    private function processRows(array $rows)
    {
        if (count($rows) < 1) return;

        $headers = array_shift($rows); //dump($headers); die();
        $codeIndex = array_search('Product Code',$headers);
        $nameIndex = array_search('Shoe Name',$headers);
        $colorIndex = array_search('Color',$headers);
        if ($codeIndex === false) return;
        foreach($rows as $rowNumber => $row) {

            $this->sheetRowNumber = $rowNumber +2;

            //dump($row); die();
            $code  = $this->filterData($row,$codeIndex);
            $name  = $this->filterData($row,$nameIndex);
            $color = $this->filterData($row,$colorIndex);

            $code = str_replace(' ','',$code);

            $this->processShoe($code,$name,$color);
        }
    }
    private function processShoe(string $code, string $name, string $color) : void
    {
        if (strlen($code) < 1) return;

        if (strlen($code) !== 9) {
            echo sprintf("INVALID Code %-8s %3d %s\n",
                $this->sheetName,$this->sheetRowNumber,$code);
            return;
        }
        // Lookup and cache
        $shoe = $this->findShoe($code);
        if ($shoe === null) {
            $shoe = new Shoe($code,$name,$color);
            $this->shoeRepository->getEntityManager()->persist($shoe);
        }
        if ($this->updateShoe) {
            $shoe->setName($name);
            $shoe->setColor($color);
        }
        //echo "{$code} {$name} {$color}\n"; die();
    }
    private function filterData(array $row, int $index) : string
    {
        $data = (string) $row[$index];
        return strtoupper(trim($data));
    }
}