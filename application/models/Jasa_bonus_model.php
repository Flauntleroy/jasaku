<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jasa_bonus_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model');
    }

    /**
     * Get user's jasa with signature status and optional year/status filters
     * @param int $user_id
     * @param int|null $year
     * @param string|null $status 'signed' | 'unsigned' | null
     * @return array
     */
    public function get_user_jasa_with_signature_filtered($user_id, $year = null, $status = null) {
        // Map user_id -> nik for backward compatibility
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return []; }
    $this->db->select('jb.*, u.nik as user_nik, u.nama, u.ruangan, u.no_rekening, tt.id as ttd_id, tt.signed_at, tt.tanda_tangan_image');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->join('tanda_tangan tt', 'jb.id = tt.jasa_bonus_id', 'left');
        $this->db->where('jb.nik', $nik);
        if (!empty($year)) {
            $this->db->where('YEAR(jb.periode) =', (int)$year, false);
        }
        if ($status === 'signed') {
            $this->db->where('tt.id IS NOT NULL', null, false);
        } else if ($status === 'unsigned') {
            $this->db->where('tt.id IS NULL', null, false);
        }
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        $rows = $query->result();
        foreach ($rows as $row) {
            $row->status_ttd = $row->ttd_id ? 'Sudah TTD' : 'Belum TTD';
        }
        return $rows;
    }

    /**
     * Get distinct years from jasa_bonus.periode for a user
     */
    public function get_years_for_user($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return []; }
        $this->db->select('DISTINCT YEAR(periode) as year', false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->order_by('year', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get all jasa bonus
    public function get_all_jasa_bonus() {
        $this->db->select('jb.*, u.nama, u.ruangan, u.asn, u.nik, u.status_ptkp, u.golongan');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get jasa bonus by user ID
    public function get_jasa_bonus_by_user($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return []; }
        $this->db->select('jb.*, u.nama, u.ruangan, u.asn, u.nik, u.status_ptkp, u.golongan');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->where('jb.nik', $nik);
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get jasa bonus by ID
    public function get_jasa_bonus_by_id($id) {
        $this->db->select('jb.*, u.nama, u.ruangan, u.asn, u.nik, u.status_ptkp, u.golongan');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->where('jb.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Create new jasa bonus
    public function create_jasa_bonus($data) {
        return $this->db->insert('jasa_bonus', $data);
    }

    // Update jasa bonus
    public function update_jasa_bonus($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('jasa_bonus', $data);
    }

    // Delete jasa bonus
    public function delete_jasa_bonus($id) {
        $this->db->where('id', $id);
        return $this->db->delete('jasa_bonus');
    }

    // Get unsigned jasa bonus by user
    public function get_unsigned_jasa_bonus_by_user($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return []; }
        $this->db->select('jb.*, u.nama, u.ruangan');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->where('jb.nik', $nik);
        $this->db->where('jb.id NOT IN (SELECT jasa_bonus_id FROM tanda_tangan)', NULL, FALSE);
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get signed jasa bonus by user
    public function get_signed_jasa_bonus_by_user($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return []; }
        $this->db->select('jb.*, u.nama, u.ruangan, tt.signed_at, tt.tanda_tangan_image');
        $this->db->from('jasa_bonus jb');
        $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->join('tanda_tangan tt', 'jb.id = tt.jasa_bonus_id');
        $this->db->where('jb.nik', $nik);
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get jasa bonus with signature status
    public function get_jasa_bonus_with_signature() {
    $this->db->select('jb.*, u.nama, u.ruangan, u.asn, u.nik, u.status_ptkp, u.golongan, 
              tt.signed_at, tt.id as ttd_id');
    $this->db->from('jasa_bonus jb');
    $this->db->join('users u', 'jb.nik = u.nik');
        $this->db->join('tanda_tangan tt', 'jb.id = tt.jasa_bonus_id', 'left');
        $this->db->order_by('jb.periode', 'DESC');
        $query = $this->db->get();
        
        $result = $query->result();
        
        // Add status_ttd manually
        foreach ($result as $row) {
            $row->status_ttd = $row->ttd_id ? 'Sudah' : 'Belum';
        }
        
        return $result;
    }

    // Get statistics for dashboard
    public function get_statistics() {
        $stats = array();
        
        // Total jasa bonus
        $stats['total_jasa'] = $this->db->count_all('jasa_bonus');
        
        // Total yang sudah ditandatangani
        $this->db->distinct();
        $this->db->select('jasa_bonus_id');
        $stats['total_signed'] = $this->db->count_all_results('tanda_tangan');
        
        // Total yang belum ditandatangani
        $stats['total_unsigned'] = $stats['total_jasa'] - $stats['total_signed'];
        
        // Total pegawai
        $stats['total_pegawai'] = $this->db->count_all('users');
        
        return $stats;
    }

    /**
     * Return monthly sums for bruto and netto for the last N months (including current month)
     * Output shape:
     * [
     *   'categories' => ['Oct 2024', 'Nov 2024', ...],
     *   'bruto' => [123, ...],
     *   'netto' => [100, ...],
     * ]
     */
    public function get_monthly_sums($months = 12) {
        $months = max(1, min(36, (int)$months));
        // Build month keys for last N months
        $labels = [];
        $keys = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $ts = strtotime(date('Y-m-01') . " -{$i} months");
            $ym = date('Y-m', $ts);
            $keys[] = $ym;
            $labels[] = date('M Y', $ts);
        }

        $startDate = date('Y-m-01', strtotime(date('Y-m-01') . ' -' . ($months - 1) . ' months'));
        $this->db->select("DATE_FORMAT(periode, '%Y-%m') as ym, SUM(terima_sebelum_pajak) as bruto, SUM(terima_setelah_pajak) as netto", false);
        $this->db->from('jasa_bonus');
        $this->db->where('periode >=', $startDate);
        $this->db->group_by('ym');
        $this->db->order_by('ym', 'ASC');
        $q = $this->db->get();
        $rows = $q->result();

        $brutoMap = array_fill_keys($keys, 0);
        $nettoMap = array_fill_keys($keys, 0);
        foreach ($rows as $r) {
            $ym = $r->ym;
            if (isset($brutoMap[$ym])) {
                $brutoMap[$ym] = (float)$r->bruto;
                $nettoMap[$ym] = (float)$r->netto;
            }
        }

        return [
            'categories' => $labels,
            'bruto' => array_values($brutoMap),
            'netto' => array_values($nettoMap),
        ];
    }

    /**
     * Calculate percentage of signed vs total jasa_bonus
     */
    public function get_signed_percentage() {
        $total = (int)$this->db->count_all('jasa_bonus');
        if ($total === 0) { return 0.0; }
        $this->db->distinct();
        $this->db->select('jasa_bonus_id');
        $signed = (int)$this->db->count_all_results('tanda_tangan');
        return round(($signed / max(1, $total)) * 100, 2);
    }

    /**
     * Monthly sums (bruto, pajak, netto) for a specific user by user_id for last N months
     * Returns ['categories'=>[...], 'bruto'=>[], 'pajak'=>[], 'netto'=>[]]
     */
    public function get_monthly_sums_for_user($user_id, $months = 12) {
        $months = max(1, min(36, (int)$months));
        // Map user_id -> nik
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return ['categories'=>[], 'bruto'=>[], 'pajak'=>[], 'netto'=>[]]; }

        $labels = [];$keys = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $ts = strtotime(date('Y-m-01') . " -{$i} months");
            $ym = date('Y-m', $ts);
            $keys[] = $ym;
            $labels[] = date('M Y', $ts);
        }
        $startDate = date('Y-m-01', strtotime(date('Y-m-01') . ' -' . ($months - 1) . ' months'));
        $this->db->select("DATE_FORMAT(periode, '%Y-%m') as ym, SUM(terima_sebelum_pajak) as bruto, SUM(pajak_5 + pajak_15 + pajak_0) as pajak, SUM(terima_setelah_pajak) as netto", false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $startDate);
        $this->db->group_by('ym');
        $this->db->order_by('ym', 'ASC');
        $rows = $this->db->get()->result();

        $brutoMap = array_fill_keys($keys, 0);
        $pajakMap = array_fill_keys($keys, 0);
        $nettoMap = array_fill_keys($keys, 0);
        foreach ($rows as $r) {
            $ym = $r->ym;
            if (isset($brutoMap[$ym])) {
                $brutoMap[$ym] = (float)$r->bruto;
                $pajakMap[$ym] = (float)$r->pajak;
                $nettoMap[$ym] = (float)$r->netto;
            }
        }
        return [
            'categories' => $labels,
            'bruto' => array_values($brutoMap),
            'pajak' => array_values($pajakMap),
            'netto' => array_values($nettoMap),
        ];
    }

    /**
     * Count signed vs pending for a user over last N months
     * Returns ['signed'=>int, 'pending'=>int]
     */
    public function get_user_signed_pending_counts($user_id, $months = 12) {
        $months = max(1, min(120, (int)$months));
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return ['signed'=>0, 'pending'=>0]; }
        $startDate = date('Y-m-01', strtotime(date('Y-m-01') . ' -' . ($months - 1) . ' months'));

        // Total within range
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $startDate);
        $total = (int)$this->db->count_all_results();

        // Signed count (distinct jasa_bonus_id in tanda_tangan that belong to user and range)
        $this->db->select('COUNT(DISTINCT tt.jasa_bonus_id) as cnt', false);
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'jb.id = tt.jasa_bonus_id');
        $this->db->where('jb.nik', $nik);
        $this->db->where('jb.periode >=', $startDate);
        $signed = (int)($this->db->get()->row()->cnt ?? 0);

        $pending = max(0, $total - $signed);
        return ['signed' => $signed, 'pending' => $pending];
    }

    /** Year-to-date netto for a user */
    public function get_user_year_to_date_netto($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return 0.0; }
        $yearStart = date('Y-01-01');
        $this->db->select('SUM(terima_setelah_pajak) as total', false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $yearStart);
        $row = $this->db->get()->row();
        return (float)($row->total ?? 0);
    }

    /** YTD pajak total untuk user */
    public function get_user_year_to_date_pajak_total($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return 0.0; }
        $yearStart = date('Y-01-01');
        $this->db->select('SUM(pajak_5 + pajak_15 + pajak_0) as total', false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $yearStart);
        $row = $this->db->get()->row();
        return (float)($row->total ?? 0);
    }

    /** Rata-rata netto YTD (menggunakan bulan yang ada tahun ini) */
    public function get_user_year_to_date_netto_avg($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return 0.0; }
        $yearStart = date('Y-01-01');
        $this->db->select('AVG(terima_setelah_pajak) as avg_netto', false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $yearStart);
        $row = $this->db->get()->row();
        return (float)($row->avg_netto ?? 0);
    }

    /** Persentase dokumen YTD yang sudah ditandatangani */
    public function get_user_year_to_date_signed_percent($user_id) {
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return 0.0; }
        $yearStart = date('Y-01-01');
        // total
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('periode >=', $yearStart);
        $total = (int)$this->db->count_all_results();
        if ($total === 0) return 0.0;
        // signed
        $this->db->select('COUNT(DISTINCT tt.jasa_bonus_id) as cnt', false);
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'jb.id = tt.jasa_bonus_id');
        $this->db->where('jb.nik', $nik);
        $this->db->where('jb.periode >=', $yearStart);
        $signed = (int)($this->db->get()->row()->cnt ?? 0);
        return round(($signed / max(1, $total)) * 100, 2);
    }

    /**
     * Monthly netto amounts for a specific user within a calendar year (Jan..Dec)
     * Returns ['categories'=>['Jan 2025',...,'Dec 2025'], 'netto'=>[0..11]]
     */
    public function get_user_monthly_netto_for_year($user_id, $year) {
        $year = (int)$year;
        if ($year < 2000 || $year > 2100) { $year = (int)date('Y'); }
        // Map user_id -> nik
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return ['categories'=>[], 'netto'=>[]]; }

        // Build labels and month keys 01..12
        $labels = [];
        $keys = [];
        for ($m = 1; $m <= 12; $m++) {
            $ts = strtotime(sprintf('%04d-%02d-01', $year, $m));
            $labels[] = date('M Y', $ts);
            $keys[] = sprintf('%04d-%02d', $year, $m);
        }

        $this->db->select("DATE_FORMAT(periode, '%Y-%m') as ym, SUM(terima_setelah_pajak) as netto", false);
        $this->db->from('jasa_bonus');
        $this->db->where('nik', $nik);
        $this->db->where('YEAR(periode) =', $year, false);
        $this->db->group_by('ym');
        $this->db->order_by('ym', 'ASC');
        $rows = $this->db->get()->result();

        $nettoMap = array_fill_keys($keys, 0);
        foreach ($rows as $r) {
            $ym = $r->ym;
            if (isset($nettoMap[$ym])) {
                $nettoMap[$ym] = (float)$r->netto;
            }
        }

        return [
            'categories' => $labels,
            'netto' => array_values($nettoMap),
        ];
    }

    // Check if jasa bonus period exists for user
    public function period_exists_for_user($user_id, $periode, $exclude_id = null) {
        // Back-compat: map to nik
        $nik = null; $u = $this->User_model->get_user_by_id($user_id); if ($u) { $nik = $u->nik; }
        if (empty($nik)) { return false; }
        $this->db->where('nik', $nik);
        $this->db->where('periode', $periode);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('jasa_bonus');
        return $query->num_rows() > 0;
    }

    // New: check by nik directly
    public function period_exists_for_nik($nik, $periode, $exclude_id = null) {
        $this->db->where('nik', $nik);
        $this->db->where('periode', $periode);
        if ($exclude_id) { $this->db->where('id !=', $exclude_id); }
        $q = $this->db->get('jasa_bonus');
        return $q->num_rows() > 0;
    }
}