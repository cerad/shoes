<?php

namespace App\Command;

use App\Entity\Shoe;
use App\Entity\ShoeStore;
use App\Misc\ShoeStoreSummaryRow;
use App\Repository\ShoeRepository;
use App\Repository\ShoeStoreRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShoeStoreDumpCommand extends Command
{
    protected static $defaultName = 'app:dump-shoe_stores';

    private ShoeStoreRepository $shoeStoreRepository;

    public function __construct(ShoeStoreRepository $shoeStoreRepository)
    {
        parent::__construct();

        $this->shoeStoreRepository = $shoeStoreRepository;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ss = new Spreadsheet();

        // Set document properties
        $ss->getProperties()
            ->setCreator('Art')
            ->setTitle('Nike Shoes');

        $shoeStores = $this->shoeStoreRepository->findAllSortedByShoe();
        $this->writeSummarySheet($ss,'Summary',$shoeStores);

        $ss->setActiveSheetIndex(0);
        $ss->removeSheetByIndex(0);
        $writer = IOFactory::createWriter($ss, 'Xlsx');
        $writer->save('./var/shoes/NikeShoeStores.xlsx');

        return Command::SUCCESS;
    }
    protected function writeSummarySheet(Spreadsheet $ss, string $sheetName, array $shoeStores) : void
    {
        $row = new ShoeStoreSummaryRow();

        $ws = new Worksheet();
        $ws->setTitle($sheetName);
        $ss->addSheet($ws);

        // Headers
        $ws->setCellValue('A1','Product Code');
        $ws->setCellValue('B1','Shoe Name');
        $ws->setCellValue('C1','Color');

        $storeCodeLetter = 'D';
        foreach($row->storeCodes as $storeCode) {
            $ws->setCellValue($storeCodeLetter . '1',$storeCode);
            $storeCodeLetter++;
        }

        // Column width
        $ws->getColumnDimension('A')->setWidth(12);
        $ws->getColumnDimension('B')->setWidth(36);
        $ws->getColumnDimension('C')->setWidth(40);

        $storeCodeLetter = 'D';
        foreach($row->storeCodes as $storeCode) {
            $ws->getColumnDimension($storeCodeLetter)->setWidth(10);
            $storeCodeLetter++;
        }

        // Format and justification
        foreach(['A:A','B:B','C:C'] as $range) {
            $style = $ws->getStyle($range);
            $style->getNumberFormat()->setBuiltInFormatCode(NumberFormat::FORMAT_TEXT);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        $storeCodeLetter = 'D';
        foreach($row->storeCodes as $storeCode) {
            $style = $ws->getStyle($storeCodeLetter . ':' . $storeCodeLetter);
            $style->getNumberFormat()->setBuiltInFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $storeCodeLetter++;
        }

        $rowNumber = 2;

        /** @var ShoeStore $shoeStore */
        foreach($shoeStores as $shoeStore) {
            if ($row->hasShoeChanged($shoeStore)) {
                $this->writeSummaryRow($ws,$row,$rowNumber++);
                $row->setShoeStore($shoeStore);
            }
            else  $row->addShoeStore($shoeStore);
        }
        $this->writeSummaryRow($ws,$row,$rowNumber++);
    }
    private function writeSummaryRow(Worksheet $ws, ShoeStoreSummaryRow $row, int $rowNumber)
    {
        $ws->setCellValue('A' . $rowNumber, $row->shoeCode);
        $ws->setCellValue('B' . $rowNumber, $row->shoeName);
        $ws->setCellValue('C' . $rowNumber, $row->shoeColor);
        $priceLetter = 'D';
        foreach($row->storeCodes as $storeCode) {
            $price = $row->getPrice($storeCode);
            $ws->setCellValue($priceLetter . $rowNumber, $price);
            $priceLetter++;
        }
    }
}
