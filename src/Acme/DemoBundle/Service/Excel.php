<?php

namespace Acme\DemoBundle\Service;

use Acme\DemoBundle\Entity\Registration;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("acme.excel")
 */
class Excel
{
    const FORMAT_DATE = 'dd/mm/yyyy';

    protected static $greyHeaderStyles = [
        'font' => ['bold' => true],
        'fill' => [
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => ['argb' => 'F2F2F2'],
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                'color' => ['argb' => 'FFCCCCCC']
            ]
        ]
    ];

    public function registrations($registrations = [])
    {
        ini_set('memory_limit', '1G');
        $headings = [
            'ID',
            'Name',
            'Email',
            'Country',
            'Items',
            'Created',
        ];
        $headingRow = 1;
        $highestHeadingCol = count($headings)-1;

        $excel = new \PHPExcel();
        $sheet = $excel->getActiveSheet();

        $sheet
          ->setTitle('Registrations')
          ->getStyle($this->cellRange('A', $headingRow, $highestHeadingCol, $headingRow))
          ->applyFromArray(self::$greyHeaderStyles);

        foreach ($headings as $col => $heading) {
            $sheet->setCellValueByColumnAndRow($col, $headingRow, $heading);
        }
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach ([4] as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(false);
        }

        foreach ($registrations as $registration) {
            /** @var $registration Registration  */
            $row = $sheet->getHighestRow() + 1;
            $col = 0;
            $sheet
                ->setCellValueByColumnAndRow($col++, $row, $registration->getId())
                ->setCellValueByColumnAndRow($col++, $row, $registration->getName())
                ->setCellValueByColumnAndRow($col++, $row, $registration->getEmail())
                ->setCellValueByColumnAndRow($col++, $row, $registration->getCountry()->translate('en')->getTitle());
            $items = [];
            foreach ($registration->getItems() as $item) {
                $items[] = $item->getTitle();
            }
            $sheet
                ->setCellValueByColumnAndRow($col++, $row, implode(', ', $items))
                ->setCellValueByColumnAndRow($col++, $row, $registration->getCreatedAt()->format('d.m.Y H:i:s'));
        }

        return $this->retrieveContent($excel);
    }

    protected function cellRange($col1, $row1, $col2, $row2)
    {
        if (is_integer($col1)) {
            $col1 = \PHPExcel_Cell::stringFromColumnIndex($col1);
        }
        if (is_integer($col2)) {
            $col2 = \PHPExcel_Cell::stringFromColumnIndex($col2);
        }

        return sprintf('%s%d:%s%d', $col1, $row1, $col2, $row2);
    }

    public function retrieveContent(\PHPExcel $excel, $disconnectAndUnset = true)
    {
        ob_start();
        \PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
        if ($disconnectAndUnset) {
            $excel->disconnectWorksheets();
            unset($excel);
        }

        return ob_get_clean();
    }
}
