<?php declare(strict_types=1);

namespace App\ShoeStore\Command;

use App\Shoe\Shoe;
use App\Shoe\ShoeRepository;
use App\ShoeStore\ShoeStore;
use App\ShoeStore\ShoeStoreRepository;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShoeStoreLoadCommand extends Command
{
    protected static $defaultName = 'app:load-shoe_stores';

    private ShoeRepository      $shoeRepository;
    private ShoeStoreRepository $shoeStoreRepository;

    /** @var array ShoeStores[] */
    private $shoeStores = [];

    public function __construct(ShoeRepository $shoeRepository, ShoeStoreRepository $shoeStoreRepository)
    {
        parent::__construct();

        $this->shoeRepository = $shoeRepository;
        $this->shoeStoreRepository = $shoeStoreRepository;
    }

    private function flush() : void
    {
        $this->shoeStoreRepository->getEntityManager()->flush();
        $this->shoeStores = [];
    }
    private function persistShoeStore(ShoeStore $shoeStore) : void
    {
        $this->shoeStoreRepository->getEntityManager()->persist($shoeStore);
        $key = $shoeStore->getStore() . ' ' . $shoeStore->getShoe()->getCode();
        $this->shoeStores[$key] = $shoeStore;
    }

    private function truncateShoeStores() : void
    {
        // Empty tables
        $conn = $this->shoeStoreRepository->getEntityManager()->getConnection();
        $conn->beginTransaction();
        $conn->executeUpdate('TRUNCATE shoe_stores;');
        $conn->commit();
    }
    private function filterData(array $row, int $index) : string
    {
        $data = (string) $row[$index];
        return strtoupper(trim($data));
    }
    private function findShoeStore(Shoe $shoe, string $storeCode) : ?ShoeStore
    {
        $key = $storeCode . ' ' . $shoe->getCode();
        if (isset( $this->shoeStores[$key])) {
            return $this->shoeStores[$key];
        }
        $shoeStore = $this->shoeStoreRepository->findOneByShoeCodeStoreCode($shoe->getCode(),$storeCode);
        if ($shoeStore) {
            $this->shoeStores[$key] = $shoeStore;
        }
        return $shoeStore;
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        //$this->truncateShoeStores();

        // Individual shoe store entries
        $inputFileName = '//home/ahundiak/Downloads/shoes/NikeShoeStores20200630a.xlsx';
        $ss = IOFactory::load($inputFileName);

        //dd($ss->getSheetNames());
        $stores = [
            'VINE0629' => 'VINE0629',
            'LBV0629'  => 'LBV0629',
            'WG0629'   => 'WG0629'
        ];
        foreach($stores as $sheetName => $storeCode) {
            $this->processSheet($ss,$sheetName, $storeCode);
        }
        ///$this->processSheet($ss,'Celb', 'CELB');
        ///$this->processSheet($ss,'WG', 'WG');
        ///$this->processSheet($ss,'INT', 'INT');
        ///$this->processSheet($ss,'Vine', 'VINE');

        //$this->processSheet($ss,'INT97');
        //$this->processSheet($ss,'Vine97');
        //$this->processSheet($ss,'LBV97');
        //$this->processSheet($ss,'Celb 97');
        //$this->processSheet($ss,'wg 97');
        return Command::SUCCESS;
    }
    private function processSheet(Spreadsheet $ss, string $sheetName, string $storeName) : void
    {
        $sheetNames = $ss->getSheetNames();
        $sheetIndex = array_search($sheetName,$sheetNames);
        if ($sheetIndex === false) return;

        $rows = $ss->getSheet($sheetIndex)->toArray();
        $this->processRows($storeName,$rows);

        $this->flush();
    }
    private function processRows(string $storeName, array $rows)
    {
        if (count($rows) < 1) return;

        $headers    = array_shift($rows); //dump($headers); die();
        $codeIndex  = array_search('Product Code',$headers);
        $priceIndex = array_search('Price',$headers);

        if ($codeIndex === false) return;

        foreach($rows as $rowNumber => $row) {

            //$sheetRowNumber = $rowNumber +2;

            $code  = $this->filterData($row,$codeIndex);
            $price  = $this->filterData($row,$priceIndex);

            $code = str_replace(' ','',$code);
            $shoe = $this->shoeRepository->findOneByCode($code);
            if ($shoe) {
                $this->processShoeStore($shoe, $storeName, $price);
            }
        }
    }
    private function processShoeStore(Shoe $shoe, string $storeCode, string $price)
    {
        // Never by a dup
        $shoeStore = $this->findShoeStore($shoe,$storeCode);
        if ($shoeStore) {
            return;
        }
        // Fool with the price
        if (strlen($price) < 1) $price = 0;
        else {
            $price = str_replace('$','',$price);
            $parts = explode('.',$price);
            if (count($parts) !== 2) $price = (int) $price;
            else {
                $price = (100 * (int)$parts[0]) + (int)$parts[1];
            }
        }
        $shoeStore = new ShoeStore($shoe,$storeCode,$price);
        $this->persistShoeStore($shoeStore);
        //dump($shoe);
        //echo "$storeCode $price\n"; die();
    }
}