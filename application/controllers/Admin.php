<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jasa_bonus_model');
        $this->load->model('Tanda_tangan_model');
        $this->load->library('form_validation');
        
        // Check if admin is logged in
        $this->check_admin_access();
    }

    private function check_admin_access() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
    }

    public function dashboard() {
        $data['title'] = 'Dashboard Admin';
        $data['page_title'] = 'Dashboard Admin';
        
        // Get statistics
        $data['stats'] = $this->Jasa_bonus_model->get_statistics();
        
        // Get recent jasa bonus
        $data['recent_jasa'] = array_slice($this->Jasa_bonus_model->get_jasa_bonus_with_signature(), 0, 5);
        
    $data['content'] = $this->load->view('admin/dashboard', $data, TRUE);
    $this->load->view('template/main', $data);
    }

    public function users() {
        $data['title'] = 'Kelola Pegawai';
        $data['page_title'] = 'Kelola Pegawai';
        
        // Handle form submission
        if ($this->input->post('action')) {
            $this->handle_user_action();
            return;
        }
        
        $data['users'] = $this->User_model->get_all_users();
        
    $data['content'] = $this->load->view('admin/users', $data, TRUE);
    $this->load->view('template/main', $data);
    }

    public function jasa_bonus() {
        $data['title'] = 'Data Jasa/Bonus';
        $data['page_title'] = 'Data Jasa/Bonus';
        
        // Handle form submission
        if ($this->input->post('action')) {
            $this->handle_jasa_bonus_action();
            return;
        }
        
        $data['jasa_bonus'] = $this->Jasa_bonus_model->get_jasa_bonus_with_signature();
        $data['users'] = $this->User_model->get_users_by_role('pegawai');
        
    $data['content'] = $this->load->view('admin/jasa_bonus', $data, TRUE);
    $this->load->view('template/main', $data);
    }

    public function laporan() {
        $data['title'] = 'Laporan';
        $data['page_title'] = 'Laporan Tanda Tangan';
        
        // Handle export
        if ($this->input->get('export')) {
            $this->export_laporan();
            return;
        }
        
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $data['laporan'] = $this->Tanda_tangan_model->get_for_export($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
    $data['content'] = $this->load->view('admin/laporan', $data, TRUE);
    $this->load->view('template/main', $data);
    }

    private function handle_user_action() {
        $action = $this->input->post('action');
        
        if ($action == 'create') {
            $this->create_user();
        } elseif ($action == 'update') {
            $this->update_user();
        } elseif ($action == 'delete') {
            $this->delete_user();
        }
    }

    private function create_user() {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[pegawai,admin]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'nama' => $this->input->post('nama', TRUE),
                'ruangan' => $this->input->post('ruangan', TRUE),
                'asn' => $this->input->post('asn', TRUE),
                'nik' => $this->input->post('nik', TRUE),
                'status_ptkp' => $this->input->post('status_ptkp', TRUE),
                'golongan' => $this->input->post('golongan', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password', TRUE),
                'role' => $this->input->post('role', TRUE)
            );
            
            if ($this->User_model->create_user($data)) {
                $this->session->set_flashdata('success', 'Pegawai berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pegawai.');
            }
        }
        
        redirect('admin/users');
    }

    private function update_user() {
        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'nama' => $this->input->post('nama', TRUE),
                'ruangan' => $this->input->post('ruangan', TRUE),
                'asn' => $this->input->post('asn', TRUE),
                'nik' => $this->input->post('nik', TRUE),
                'status_ptkp' => $this->input->post('status_ptkp', TRUE),
                'golongan' => $this->input->post('golongan', TRUE),
                'username' => $this->input->post('username', TRUE),
                'role' => $this->input->post('role', TRUE)
            );
            
            // Only update password if provided
            if (!empty($this->input->post('password'))) {
                $data['password'] = $this->input->post('password', TRUE);
            }
            
            if ($this->User_model->update_user($id, $data)) {
                $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data pegawai.');
            }
        }
        
        redirect('admin/users');
    }

    private function delete_user() {
        $id = $this->input->post('id');
        
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Pegawai berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pegawai.');
        }
        
        redirect('admin/users');
    }

    private function handle_jasa_bonus_action() {
        $action = $this->input->post('action');
        
        if ($action == 'create') {
            $this->create_jasa_bonus();
        } elseif ($action == 'update') {
            $this->update_jasa_bonus();
        } elseif ($action == 'delete') {
            $this->delete_jasa_bonus();
        }
    }

    private function create_jasa_bonus() {
        $this->form_validation->set_rules('user_id', 'Pegawai', 'required');
        $this->form_validation->set_rules('periode', 'Periode', 'required');
        $this->form_validation->set_rules('terima_sebelum_pajak', 'Terima Sebelum Pajak', 'required|numeric');
        $this->form_validation->set_rules('terima_setelah_pajak', 'Terima Setelah Pajak', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $user_id = $this->input->post('user_id');
            $periode = $this->input->post('periode');
            
            // Check if period already exists for this user
            if ($this->Jasa_bonus_model->period_exists_for_user($user_id, $periode)) {
                $this->session->set_flashdata('error', 'Data jasa/bonus untuk periode ini sudah ada untuk pegawai tersebut.');
            } else {
                $data = array(
                    'user_id' => $user_id,
                    'periode' => $periode,
                    'terima_sebelum_pajak' => $this->input->post('terima_sebelum_pajak'),
                    'pajak_5' => $this->input->post('pajak_5') ?: 0,
                    'pajak_15' => $this->input->post('pajak_15') ?: 0,
                    'pajak_0' => $this->input->post('pajak_0') ?: 0,
                    'terima_setelah_pajak' => $this->input->post('terima_setelah_pajak')
                );
                
                if ($this->Jasa_bonus_model->create_jasa_bonus($data)) {
                    $this->session->set_flashdata('success', 'Data jasa/bonus berhasil ditambahkan.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan data jasa/bonus.');
                }
            }
        }
        
        redirect('admin/jasa-bonus');
    }

    private function update_jasa_bonus() {
        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('terima_sebelum_pajak', 'Terima Sebelum Pajak', 'required|numeric');
        $this->form_validation->set_rules('terima_setelah_pajak', 'Terima Setelah Pajak', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'terima_sebelum_pajak' => $this->input->post('terima_sebelum_pajak'),
                'pajak_5' => $this->input->post('pajak_5') ?: 0,
                'pajak_15' => $this->input->post('pajak_15') ?: 0,
                'pajak_0' => $this->input->post('pajak_0') ?: 0,
                'terima_setelah_pajak' => $this->input->post('terima_setelah_pajak')
            );
            
            if ($this->Jasa_bonus_model->update_jasa_bonus($id, $data)) {
                $this->session->set_flashdata('success', 'Data jasa/bonus berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data jasa/bonus.');
            }
        }
        
        redirect('admin/jasa-bonus');
    }

    private function delete_jasa_bonus() {
        $id = $this->input->post('id');
        
        if ($this->Jasa_bonus_model->delete_jasa_bonus($id)) {
            $this->session->set_flashdata('success', 'Data jasa/bonus berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data jasa/bonus.');
        }
        
        redirect('admin/jasa-bonus');
    }

    private function export_laporan() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $laporan = $this->Tanda_tangan_model->get_for_export($start_date, $end_date);
        
        // Load PHPSpreadsheet or create CSV export
        $this->load->library('csv_export');
        $this->csv_export->export_laporan($laporan, $start_date, $end_date);
    }
}