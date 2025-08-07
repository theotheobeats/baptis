<?php

namespace App\Helpers;

use App\Exports\CollectionExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Exceptions\LaravelExcelException;
use Maatwebsite\Excel\Facades\Excel;

class ExportHelper
{
    const TYPE_EXCEL = "excel";
    const TYPE_PDF = "pdf";

    public static function export(
        $type,
        $fileName,
        $data,
        $view,
        $request,
        $paperOption = null,
        $filters = [],
    ) {
        if ($type == self::TYPE_EXCEL) {
            try {
                return Excel::download(new CollectionExport($data, $view), "$fileName.xlsx");
            } catch (LaravelExcelException $e) {
                // Handle the Laravel Excel exception
                dd($e->getMessage());
            }catch (\Exception $e) {
                // Log or print the exception message for debugging
                dd($e->getMessage());
            }
        } else {
            $pdf = Pdf::loadview(
                $view,
                $data
            );

            if ($paperOption) {
                $pdf = $pdf->setPaper($paperOption['size'], $paperOption['orientation']);
            }

            return $pdf->download($fileName . ".pdf");
        }
    }
}
