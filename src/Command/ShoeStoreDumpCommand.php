<?php

namespace App\Command;

use App\Shoe\Shoe;
use App\Shoe\ShoeRepository;
use App\ShoeStore\ShoeStore;
use App\ShoeStore\ShoeStoreRepository;
use App\ShoeStore\ShoeStoreSummaryRow;

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

    //private array $storeCodes = ['CELB', 'WG', 'INT', 'VINE'];
    private array $storeCodes = ['VINE0629', 'LBV0629', 'WG0629'];

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
            ->setTitle('Nike Shoe Stores');

        $shoeStores = $this->shoeStoreRepository->findAllForStoreCodes($this->storeCodes);
        $this->writeSummarySheet($ss,'Summary',$shoeStores);

        foreach($this->storeCodes as $storeCode) {
            $this->writeStoreSheet($ss,$storeCode);
        }

        $ss->setActiveSheetIndex(0);
        $ss->removeSheetByIndex(0);
        $writer = IOFactory::createWriter($ss, 'Xlsx');
        $writer->save('./var/shoes/NikeShoeStores.xlsx');

        return Command::SUCCESS;
    }
    public function writeStoreSheet(Spreadsheet $ss, string $storeCode) : void
    {
        // New Sheet
        $ws = new Worksheet();
        $ws->setTitle($storeCode);
        $ss->addSheet($ws);

        // Headers
        $ws->setCellValue('A1','Product Code');
        $ws->setCellValue('B1','Shoe Name');
        $ws->setCellValue('C1','Color');
        $ws->setCellValue('D1','Price');

        // Column width
        $ws->getColumnDimension('A')->setWidth(12);
        $ws->getColumnDimension('B')->setWidth(36);
        $ws->getColumnDimension('C')->setWidth(40);
        $ws->getColumnDimension('D')->setWidth(10);

        // Format and justification
        foreach(['A:A','B:B','C:C'] as $range) {
            $style = $ws->getStyle($range);
            $style->getNumberFormat()->setBuiltInFormatCode(NumberFormat::FORMAT_TEXT);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        $style = $ws->getStyle('D:D');
        $style->getNumberFormat()->setBuiltInFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $shoeStores = $this->shoeStoreRepository->findAllForStore($storeCode);

        $rowNumber = 2;

        foreach($shoeStores as $shoeStore) {
                $this->writeStoreRow($ws,$shoeStore,$rowNumber++);
        }
    }
    private function writeStoreRow(Worksheet $ws, ShoeStore $shoeStore, int $rowNumber)
    {
        $shoe = $shoeStore->getShoe();
        $ws->setCellValue('A' . $rowNumber, $shoe->getCodeColor());
        $ws->setCellValue('B' . $rowNumber, $shoe->getName());
        $ws->setCellValue('C' . $rowNumber, $shoe->getColor());
        $ws->setCellValue('D' . $rowNumber, $shoeStore->getPriceCurrency());
    }
    public function writeSummarySheet(Spreadsheet $ss, string $sheetName, array $shoeStores) : void
    {
        $row = new ShoeStoreSummaryRow($this->storeCodes);

        $ws = new Worksheet();
        $ws->setTitle($sheetName);
        $ss->addSheet($ws);

        // Headers
        $ws->setCellValue('A1','Product Code');
        $ws->setCellValue('B1','Shoe Name');
        $ws->setCellValue('C1','Color');

        $storeCodeLetter = 'D';
        foreach($this->storeCodes as $storeCode) {
            $ws->setCellValue($storeCodeLetter . '1',$storeCode);
            $storeCodeLetter++;
        }

        // Column width
        $ws->getColumnDimension('A')->setWidth(12);
        $ws->getColumnDimension('B')->setWidth(36);
        $ws->getColumnDimension('C')->setWidth(40);

        $storeCodeLetter = 'D';
        foreach($this->storeCodes as $storeCode) {
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
        foreach($this->storeCodes as $storeCode) {
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
        foreach($this->storeCodes as $storeCode) {
            $price = $row->getPriceForStore($storeCode);
            $ws->setCellValue($priceLetter . $rowNumber, $price);
            $priceLetter++;
        }
    }
}
