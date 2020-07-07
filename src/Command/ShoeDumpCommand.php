<?php declare(strict_types=1);

namespace App\Command;

use App\Shoe\Shoe;
use App\Shoe\ShoeRepository;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShoeDumpCommand extends Command
{
    protected static $defaultName = 'app:dump-shoes';

    private ShoeRepository $shoeRepository;

    public function __construct(ShoeRepository $shoeRepository)
    {
        parent::__construct();

        $this->shoeRepository = $shoeRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ss = new Spreadsheet();

        // Set document properties
        $ss->getProperties()
            ->setCreator('Art')
            ->setTitle('Nike Shoes');

        $shoes = $this->shoeRepository->findBy([],['code' => 'ASC']);
        $this->writeSheet($ss,'Code',$shoes);

        $shoes = $this->shoeRepository->findBy([],['name' => 'ASC']);
        $this->writeSheet($ss,'Name',$shoes);

        $ss->setActiveSheetIndex(0);
        $ss->removeSheetByIndex(0);
        $writer = IOFactory::createWriter($ss, 'Xlsx');
        $writer->save('./var/shoes/NikeShoes.xlsx');

        return Command::SUCCESS;
    }
    protected function writeSheet(Spreadsheet $ss, string $sheetName, array $shoes) : void
    {
        $ws = new Worksheet();
        $ws->setTitle($sheetName);
        $ss->addSheet($ws);

        $ws->setCellValue('A1','Product Code');
        $ws->setCellValue('B1','Shoe Name');
        $ws->setCellValue('C1','Color');

        $ws->getColumnDimension('A')->setWidth(12);
        $ws->getColumnDimension('B')->setWidth(36);
        $ws->getColumnDimension('C')->setWidth(40);

        foreach(['A:A','B:B','C:C'] as $range) {
            $style = $ws->getStyle($range);
            $style->getNumberFormat()->setBuiltInFormatCode(NumberFormat::FORMAT_TEXT);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        $row = 1;
        /** @var Shoe $shoe */
        foreach($shoes as $shoe) {
            $row++;
            $ws->setCellValue('A' . $row,$shoe->getCodeColor());
            $ws->setCellValue('B' . $row,$shoe->getName());
            $ws->setCellValue('C' . $row,$shoe->getColor());
        }
    }
}