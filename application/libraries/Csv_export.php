<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv_export {
    /** @var CI_Controller */
    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
     * Export laporan data to CSV and force download
     * @param array $laporan Array of stdClass rows from Tanda_tangan_model->get_for_export
     * @param string|null $start_date
     * @param string|null $end_date
     * @return void
     */
    public function export_laporan($laporan, $start_date = null, $end_date = null) {
        $filename = 'laporan_ttd_' . date('Ymd_His');
        if ($start_date || $end_date) {
            $filename .= '_' . ($start_date ?: 'all') . '-' . ($end_date ?: 'all');
        }
        $filename .= '.csv';

        // Clean output buffer if any to prevent corrupt CSV
        if (ob_get_level()) {
            @ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header row
        fputcsv($output, [
            'Tanggal TTD',
            'Nama',
            'Ruangan',
            'Periode',
            'Terima Sebelum Pajak',
            'Pajak 5%',
            'Pajak 15%',
            'Pajak 0%',
            'Terima Setelah Pajak',
        ]);

        if (!empty($laporan)) {
            foreach ($laporan as $row) {
                fputcsv($output, [
                    isset($row->signed_at) ? $row->signed_at : '',
                    isset($row->nama) ? $row->nama : '',
                    isset($row->ruangan) ? $row->ruangan : '',
                    isset($row->periode) ? $row->periode : '',
                    isset($row->terima_sebelum_pajak) ? $row->terima_sebelum_pajak : 0,
                    isset($row->pajak_5) ? $row->pajak_5 : 0,
                    isset($row->pajak_15) ? $row->pajak_15 : 0,
                    isset($row->pajak_0) ? $row->pajak_0 : 0,
                    isset($row->terima_setelah_pajak) ? $row->terima_setelah_pajak : 0,
                ]);
            }
        }

        fclose($output);
        exit;
    }
}
