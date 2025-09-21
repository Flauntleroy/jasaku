<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index() {
        // Always redirect to login page for index
        $this->login();
    }

    public function clear_session() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        // Proses form login
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
            } else {
                $username = $this->input->post('username', TRUE);
                $password = $this->input->post('password', TRUE);

                $user = $this->User_model->login($username, $password);

                if ($user) {
                    // Set session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'nama' => $user->nama,
                        'role' => $user->role,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);

                    // Redirect berdasarkan role
                    $this->_redirect_by_role();
                    return;
                } else {
                    $data['error'] = 'Username atau password salah!';
                }
            }
        }

        // Load view login
        $this->load->view('auth/login', isset($data) ? $data : array());
    }

    public function logout() {
        // Hapus session
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    private function _redirect_by_role() {
        $role = $this->session->userdata('role');
        
        if ($role == 'admin') {
            redirect('admin/dashboard');
        } else {
            redirect('pegawai/dashboard');
        }
    }

    // Method untuk check apakah user sudah login
    public function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Method untuk check apakah user adalah admin
    public function check_admin() {
        $this->check_login();
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
    }

    // Method untuk check apakah user adalah pegawai
    public function check_pegawai() {
        $this->check_login();
        if ($this->session->userdata('role') != 'pegawai') {
            show_404();
        }
    }
}