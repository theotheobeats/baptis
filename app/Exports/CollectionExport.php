<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;

class CollectionExport implements FromView, ShouldAutoSize, WithStrictNullComparison, WithEvents, WithTitle
{
    private $view;
    private $data;

    public function __construct($data, $view, $filters = [])
    {
        $this->data = $data;
        $this->view = $view;
    }

    public function view(): View
    {
        return view($this->view, $this->data);
    }

    public function title(): string
    {
        return 'Export';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();

                $firstBRow = null;
                $consecutiveEmptyRows = 0;
                $rangeStart = null;
                $rangeEnd = null;

                for ($row = 1; $row <= $lastRow; $row++) {
                    $cellValue = $event->sheet->getCellByColumnAndRow(2, $row)->getValue();
                    
                    if ($cellValue !== null && $cellValue !== "") {
                        $firstBRow = $row;
                        break;
                    }
                }

                if ($firstBRow !== null) {
                    for ($row = $firstBRow; $row <= $lastRow; $row++) {
                        $isACellMerge = $event->sheet->getCellByColumnAndRow(1, $row)->getMergeRange();
                        $cellValue = $event->sheet->getCellByColumnAndRow(2, $row)->getValue();

                        if (($cellValue === null || $cellValue === "") && !$isACellMerge) {
                            $consecutiveEmptyRows++;
                        } else {
                            $consecutiveEmptyRows = 0;
                        }

                        if ($consecutiveEmptyRows < 2) {
                            if ($rangeStart === null) {
                                $rangeStart = $row;
                            }
                            $rangeEnd = $row;
                        } else {
                            if ($rangeStart !== null && $rangeEnd !== null) {
                                $range = 'A' . $rangeStart . ':' . $lastColumn . ($rangeEnd - 1);
                                $event->sheet->getStyle($range)->applyFromArray([
                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                            'color' => ['argb' => 'FF000000'],
                                        ],
                                    ],
                                ]);
                            }

                            $rangeStart = null;
                            $rangeEnd = null;
                        }
                    }

                    if ($rangeStart !== null && $rangeEnd !== null) {
                        $range = 'A' . $rangeStart . ':' . $lastColumn . $rangeEnd;
                        $event->sheet->getStyle($range)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                            ],
                        ]);
                    }
                }
            },
        ];
    }

}
