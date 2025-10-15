<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanda_tangan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model');
    }

    // Create new tanda tangan
    public function create_tanda_tangan($data) {
        return $this->db->insert('tanda_tangan', $data);
    }

    // Get tanda tangan by jasa bonus ID
    public function get_by_jasa_bonus_id($jasa_bonus_id) {
        $this->db->where('jasa_bonus_id', $jasa_bonus_id);
        $query = $this->db->get('tanda_tangan');
        return $query->row();
    }

    // Get tanda tangan by user (back-compat): map user_id -> nik and query by nik
    public function get_by_user_id($user_id) {
        $u = $this->User_model->get_user_by_id($user_id);
        if (!$u) { return []; }
        $this->db->select('tt.*, jb.periode, jb.terima_sebelum_pajak, jb.terima_setelah_pajak');
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'tt.jasa_bonus_id = jb.id');
        $this->db->where('tt.nik', $u->nik);
        $this->db->order_by('tt.signed_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Check if jasa bonus already signed
    public function is_already_signed($jasa_bonus_id) {
        $this->db->where('jasa_bonus_id', $jasa_bonus_id);
        $query = $this->db->get('tanda_tangan');
        return $query->num_rows() > 0;
    }

    // Get all tanda tangan with details
    public function get_all_with_details() {
        $this->db->select('tt.*, jb.periode, jb.terima_sebelum_pajak, jb.terima_setelah_pajak, 
                          u.nama, u.ruangan, u.nik');
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'tt.jasa_bonus_id = jb.id');
        $this->db->join('users u', 'tt.nik = u.nik');
        $this->db->order_by('tt.signed_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Delete tanda tangan
    public function delete_tanda_tangan($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tanda_tangan');
    }
    
    // Delete tanda tangan by jasa_bonus_id
    public function delete_by_jasa_bonus_id($jasa_bonus_id) {
        $this->db->where('jasa_bonus_id', $jasa_bonus_id);
        return $this->db->delete('tanda_tangan');
    }

    // Get tanda tangan by ID
    public function get_by_id($id) {
        $this->db->select('tt.*, jb.periode, jb.terima_sebelum_pajak, jb.terima_setelah_pajak, 
                          u.nama, u.ruangan, u.nik');
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'tt.jasa_bonus_id = jb.id');
        $this->db->join('users u', 'tt.nik = u.nik');
        $this->db->where('tt.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get signatures for export
    public function get_for_export($start_date = null, $end_date = null) {
        $this->db->select('tt.signed_at, tt.tanda_tangan_image, jb.periode, jb.terima_sebelum_pajak, jb.pajak_5, 
                          jb.pajak_15, jb.pajak_0, jb.terima_setelah_pajak,
                          u.nama, u.ruangan, u.asn, u.nik, u.status_ptkp, u.golongan');
        $this->db->from('tanda_tangan tt');
        $this->db->join('jasa_bonus jb', 'tt.jasa_bonus_id = jb.id');
        $this->db->join('users u', 'tt.nik = u.nik');
        
        if ($start_date) {
            $this->db->where('jb.periode >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('jb.periode <=', $end_date);
        }
        
        $this->db->order_by('jb.periode', 'DESC');
        $this->db->order_by('u.nama', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}