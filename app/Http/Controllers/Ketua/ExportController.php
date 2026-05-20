<?php

namespace App\Http\Controllers\Ketua;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportController extends DetailController
{
    public function export(Request $request, string $type)
    {
        $premiumIds = Invoice::where('is_accepted', true)
            ->pluck('user_id')->unique()->values()->all();

        [$rows, $columns, $title] = match ($type) {
            'member'     => $this->memberData($request, $premiumIds),
            'konten'     => $this->kontenData($request),
            'blog'       => $this->blogData($request),
            'pertanyaan' => $this->pertanyaanData($request),
            'payment'    => $this->paymentData($request),
            default      => abort(404),
        };

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Filter out sort-only columns (columns that start with an underscore but do NOT have a display key)
        $exportColumns = array_filter($columns, function($col) {
            return !str_starts_with($col['key'], '_') || isset($col['display']);
        });
        
        // Re-index columns array
        $exportColumns = array_values($exportColumns);

        // Header Row
        // A1 is for the row index
        $sheet->setCellValue('A1', 'No');
        
        $colIdx = 2; // Column B onwards
        foreach ($exportColumns as $col) {
            $colLetter = Coordinate::stringFromColumnIndex($colIdx);
            $sheet->setCellValue($colLetter . '1', $col['label']);
            $colIdx++;
        }

        // Data Rows
        $rowIdx = 2;
        foreach ($rows as $index => $row) {
            $sheet->setCellValue('A' . $rowIdx, $index + 1);
            
            $colIdx = 2;
            foreach ($exportColumns as $col) {
                $colLetter = Coordinate::stringFromColumnIndex($colIdx);
                
                // Get the correct key/display value from row
                $key = $col['display'] ?? $col['key'];
                $val = $row[$key] ?? '-';
                
                $sheet->setCellValue($colLetter . $rowIdx, $val);
                $colIdx++;
            }
            $rowIdx++;
        }

        // Auto-size columns based on contents
        $totalCols = count($exportColumns) + 1;
        for ($i = 1; $i <= $totalCols; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Professional Styling
        $lastRow = $rowIdx - 1;
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);
        $headerRange = 'A1:' . $lastColLetter . '1';
        $fullRange = 'A1:' . $lastColLetter . $lastRow;

        // Bold headers
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        
        // Light grey background for header
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF2F2F2');
            
        // Align center for "No" column
        $sheet->getStyle('A1:A' . $lastRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Thin borders for the whole table
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD3D3D3'],
                ],
            ],
        ];
        $sheet->getStyle($fullRange)->applyFromArray($styleArray);

        // Define filename
        $fileName = str_replace(' ', '_', strtolower($title)) . '_' . date('Ymd_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
