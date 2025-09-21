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
        $this->db->select('jb.*, u.nama, u.ruangan, tt.id as ttd_id, tt.signed_at, tt.tanda_tangan_image');
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