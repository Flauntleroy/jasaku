<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Excel_export {
    private function cleanForDownload() {
        @ini_set('zlib.output_compression', 'Off');
        @ini_set('output_buffering', 'Off');
        @ini_set('implicit_flush', '0');
        @ini_set('display_errors', '0');
        if (function_exists('apache_setenv')) { @apache_setenv('no-gzip', '1'); }
        if (function_exists('ob_get_level')) { while (ob_get_level() > 0) { @ob_end_clean(); } }
    }
    /**
     * Export Jasa/Bonus to an XLSX matching the provided header format.
     * @param array $rows Array of stdClass or arrays with fields: nama, ruangan, asn, nik, status_ptkp, golongan,
     *                     terima_sebelum_pajak, pajak_5, pajak_15, pajak_0, terima_setelah_pajak, periode
     * @param string|null $periodeTitle Optional title to show period/month range
     */
    public function export_jasa_bonus(array $rows, $periodeTitle = null) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Column letters: A-L
        // Header rows (2 rows with merged cells similar to screenshot)
        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Nama')
              ->setCellValue('C1', 'Ruangan')
              ->setCellValue('D1', 'ASN')
              ->setCellValue('E1', 'NIK')
              ->setCellValue('F1', 'STATUS PTKP')
              ->setCellValue('G1', 'Golongan')
              ->setCellValue('H1', 'Terima Sebelum Pajak')
              ->setCellValue('I1', 'Pajak')
              ->setCellValue('L1', 'Terima Setelah Pajak');
        // Second row for Pajak breakdown
        $sheet->setCellValue('I2', '5% (Gol III, Gol 9, Gol 10)')
              ->setCellValue('J2', '15% (Gol IV)')
              ->setCellValue('K2', '0 (Gol II, Gol 6/7, Honor)');

        // Merge cells per the layout
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:G2');
        $sheet->mergeCells('H1:H2');
        $sheet->mergeCells('I1:K1');
        $sheet->mergeCells('L1:L2');

        // Styling
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:L2')->applyFromArray($headerStyle);

        // Column widths
        $widths = [
            'A' => 6,'B' => 22,'C' => 18,'D' => 8,'E' => 18,'F' => 14,'G' => 12,
            'H' => 18,'I' => 16,'J' => 16,'K' => 20,'L' => 18,
        ];
        foreach ($widths as $col => $w) { $sheet->getColumnDimension($col)->setWidth($w); }

        // Optional title above header
        if ($periodeTitle) {
            $sheet->insertNewRowBefore(1, 1); // shift down one row
            $sheet->mergeCells('A1:L1');
            $sheet->setCellValue('A1', $periodeTitle);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        $startRow = $periodeTitle ? 3 : 2; // header height is 2 rows
        $rowIdx = $startRow + 1;
        $no = 1;
        foreach ($rows as $r) {
            // Support stdClass and arrays
            $get = function($key) use ($r) {
                if (is_array($r)) return isset($r[$key]) ? $r[$key] : null;
                if (is_object($r)) return isset($r->$key) ? $r->$key : null;
                return null;
            };

            $sheet->setCellValueExplicit('A'.$rowIdx, $no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValue('B'.$rowIdx, $get('nama'));
            $sheet->setCellValue('C'.$rowIdx, $get('ruangan'));
            $sheet->setCellValue('D'.$rowIdx, $get('asn'));
            $sheet->setCellValueExplicit('E'.$rowIdx, (string)$get('nik'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F'.$rowIdx, $get('status_ptkp'));
            $sheet->setCellValue('G'.$rowIdx, $get('golongan'));
            $sheet->setCellValue('H'.$rowIdx, (float)$get('terima_sebelum_pajak'));
            $sheet->setCellValue('I'.$rowIdx, (float)$get('pajak_5'));
            $sheet->setCellValue('J'.$rowIdx, (float)$get('pajak_15'));
            $sheet->setCellValue('K'.$rowIdx, (float)$get('pajak_0'));
            $sheet->setCellValue('L'.$rowIdx, (float)$get('terima_setelah_pajak'));
            $no++; $rowIdx++;
        }

        // Number formats for currency columns
        $sheet->getStyle('H'.($startRow+1).':L'.($rowIdx-1))
              ->getNumberFormat()->setFormatCode('#,##0');
        // Borders for body
        if ($rowIdx > $startRow+1) {
            $sheet->getStyle('A'.($startRow+1).':L'.($rowIdx-1))
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Stream as download via temp file to avoid buffer issues
        $filename = 'JasaBonus_'.date('Ymd_His').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer->save($tmp);
        $this->cleanForDownload();
        $data = @file_get_contents($tmp);
        @unlink($tmp);
        $CI = &get_instance();
        $CI->load->helper('download');
        force_download($filename, $data);
    }

    /**
     * Export Laporan TTD to XLSX, embedding signature images when available.
     * @param array $laporan Rows from Tanda_tangan_model->get_for_export (must include tanda_tangan_image path if any)
     * @param string|null $start_date
     * @param string|null $end_date
     * @return void
     */
    public function export_laporan($laporan, $start_date = null, $end_date = null) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Optional title
        $titleParts = [];
        if ($start_date) { $titleParts[] = 'dari '.$start_date; }
        if ($end_date) { $titleParts[] = 'sampai '.$end_date; }
        $title = 'Laporan Tanda Tangan';
        if (!empty($titleParts)) { $title .= ' ('.implode(' ', $titleParts).')'; }
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Header row at row 2
        $headers = [
            'A2' => 'No',
            'B2' => 'Tanggal TTD',
            'C2' => 'Nama',
            'D2' => 'Ruangan',
            'E2' => 'Periode',
            'F2' => 'Sblm Pajak',
            'G2' => 'Pajak 5%',
            'H2' => 'Pajak 15%',
            'I2' => 'Pajak 0%',
            'J2' => 'Stlh Pajak',
            'K2' => 'Tanda Tangan',
        ];
        foreach ($headers as $cell => $text) { $sheet->setCellValue($cell, $text); }
        $sheet->getStyle('A2:K2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']]
        ]);

        // Column widths
        $widths = ['A'=>6,'B'=>18,'C'=>22,'D'=>18,'E'=>14,'F'=>16,'G'=>12,'H'=>12,'I'=>12,'J'=>16,'K'=>24];
        foreach ($widths as $col => $w) { $sheet->getColumnDimension($col)->setWidth($w); }

        // Body rows start at 3
        $row = 3; $no = 1;
        foreach ((array)$laporan as $r) {
            $sheet->setCellValue('A'.$row, $no);
            $sheet->setCellValue('B'.$row, isset($r->signed_at) ? $r->signed_at : '');
            $sheet->setCellValue('C'.$row, isset($r->nama) ? $r->nama : '');
            $sheet->setCellValue('D'.$row, isset($r->ruangan) ? $r->ruangan : '');
            $sheet->setCellValue('E'.$row, isset($r->periode) ? $r->periode : '');
            $sheet->setCellValue('F'.$row, isset($r->terima_sebelum_pajak) ? (float)$r->terima_sebelum_pajak : 0);
            $sheet->setCellValue('G'.$row, isset($r->pajak_5) ? (float)$r->pajak_5 : 0);
            $sheet->setCellValue('H'.$row, isset($r->pajak_15) ? (float)$r->pajak_15 : 0);
            $sheet->setCellValue('I'.$row, isset($r->pajak_0) ? (float)$r->pajak_0 : 0);
            $sheet->setCellValue('J'.$row, isset($r->terima_setelah_pajak) ? (float)$r->terima_setelah_pajak : 0);

            // Embed signature image if exists
            $imgRel = isset($r->tanda_tangan_image) ? $r->tanda_tangan_image : null;
            if ($imgRel) {
                $imgRel = ltrim($imgRel, '/');
                $abs = defined('FCPATH') ? FCPATH . $imgRel : $imgRel;
                if (is_file($abs)) {
                    try {
                        $drawing = new Drawing();
                        $drawing->setPath($abs);
                        $drawing->setHeight(60); // px
                        $drawing->setCoordinates('K'.$row);
                        $drawing->setOffsetX(5);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);
                        // Set row height to fit image
                        $sheet->getRowDimension($row)->setRowHeight(48);
                    } catch (\Throwable $e) {
                        // Ignore image errors, leave cell blank
                    }
                }
            }

            $row++; $no++;
        }

        // Number formats
        if ($row > 3) {
            $sheet->getStyle('F3:J'.($row-1))->getNumberFormat()->setFormatCode('#,##0');
            // Borders for body
            $sheet->getStyle('A3:K'.($row-1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Output
        $filename = 'Laporan_TTD_'.date('Ymd_His').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer->save($tmp);
        $this->cleanForDownload();
        $data = @file_get_contents($tmp);
        @unlink($tmp);
        $CI = &get_instance();
        $CI->load->helper('download');
        force_download($filename, $data);
    }
}
