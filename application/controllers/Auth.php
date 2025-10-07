<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_DB_query_builder $db
 * @property User_model $User_model
 * @property Whatsapp_sender $whatsapp_sender
 */
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
                    // Handle pending activation marker
                    if (is_object($user) && isset($user->pending_activation) && $user->pending_activation) {
                        $this->session->set_flashdata('error', 'Akun Anda belum aktif. Silakan aktivasi terlebih dahulu.');
                        redirect('auth/activate');
                        return;
                    }
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
                    // Check if username exists but account is inactive
                    $this->db->where('username', $username);
                    $q = $this->db->get('users');
                    if ($q->num_rows() === 1) {
                        $row = $q->row();
                        if ($row->role !== 'admin' && (int)($row->is_active ?? 0) === 0) {
                            $data['error'] = 'Akun belum aktif. Silakan gunakan menu Aktivasi akun.';
                        } else {
                            $data['error'] = 'Username atau password salah!';
                        }
                    } else {
                        $data['error'] = 'Username atau password salah!';
                    }
                }
            }
        }

        // Load view login
        $this->load->view('auth/login', isset($data) ? $data : array());
    }

    // Activation flow by WhatsApp phone + activation code -> set new password
    public function activate() {
        // If logged in already, just redirect
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        $mode = $this->input->post('mode');
        $data = [];
        if (!$mode) { $mode = 'ask_phone'; }

        if ($mode === 'ask_phone') {
            if ($this->input->post()) {
                $this->form_validation->set_rules('phone', 'Nomor WhatsApp', 'required|trim');
                // nik optional: used when phone not found in DB to bind phone to the user
                if ($this->input->post('nik')) {
                    $this->form_validation->set_rules('nik', 'NIK', 'trim');
                }
                if ($this->form_validation->run() == FALSE) {
                    $data['error'] = validation_errors();
                } else {
                    $phone = $this->input->post('phone', TRUE);
                    $user = $this->User_model->get_user_by_phone($phone);
                    if (!$user) {
                        // Try bind by NIK if provided
                        $nik = $this->input->post('nik', TRUE);
                        if (!empty($nik)) {
                            $userByNik = $this->User_model->get_user_by_nik($nik);
                            if ($userByNik) {
                                // Save phone into that user
                                $this->User_model->update_user($userByNik->id, ['phone' => $phone]);
                                $user = $userByNik;
                            }
                        }
                    }
                    if ($user) {
                        // Jika akun sudah aktif, arahkan ke login dan jangan ubah status aktif
                        if ((int)$user->is_active === 1) {
                            $this->session->set_flashdata('success', 'Akun Anda sudah aktif. Silakan login.');
                            redirect('auth/login');
                            return;
                        }
                        // Generate (atau gunakan ulang) kode aktivasi untuk akun yang belum aktif
                        $code = $user->activation_code;
                        if (empty($code)) {
                            $code = $this->User_model->set_activation_code($user->id);
                        }
                        if (!$code) {
                            $data['error'] = 'Gagal menyiapkan kode aktivasi.';
                        } else {
                            // Send via WhatsApp (best-effort)
                            $this->load->library('whatsapp_sender');
                            $appName = 'Sistem TTD Jasa/Bonus';
                            $msg = "Halo {$user->nama},\nKode aktivasi {$appName}: {$code}.\nMasukkan kode ini untuk aktivasi akun.";
                            $res = $this->whatsapp_sender->send($phone, $msg);
                            if (!$res['success']) {
                                $data['error'] = 'Gagal mengirim WhatsApp: ' . (is_string($res['body']) ? $res['body'] : '');
                                $data['phone'] = $phone;
                                $mode = 'ask_phone';
                            } else {
                                $data['phone'] = $phone;
                                $mode = 'verify_code';
                            }
                        }
                    } else {
                        $data['error'] = 'Nomor belum terdaftar. Masukkan NIK untuk mengaitkan nomor ini.';
                        $data['ask_nik'] = true;
                    }
                }
            }
        } elseif ($mode === 'verify_code') {
            $this->form_validation->set_rules('phone', 'Nomor WhatsApp', 'required|trim');
            $this->form_validation->set_rules('activation_code', 'Kode Aktivasi', 'required|trim|regex_match[/^\d{6}$/]');
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
                $mode = 'verify_code';
            } else {
                $phone = $this->input->post('phone', TRUE);
                $code = $this->input->post('activation_code', TRUE);
                $user = $this->User_model->verify_activation_code_by_phone($phone, $code);
                if (!$user) {
                    $data['error'] = 'Kode salah atau kadaluarsa.';
                    $data['phone'] = $phone;
                    $mode = 'verify_code';
                } else {
                    $data['user_id'] = $user->id;
                    $mode = 'set_password';
                }
            }
        } elseif ($mode === 'set_password') {
            $this->form_validation->set_rules('user_id', 'User', 'required|integer');
            $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
                $data['user_id'] = $this->input->post('user_id');
                $mode = 'set_password';
            } else {
                $uid = (int)$this->input->post('user_id');
                $pass = $this->input->post('password', TRUE);
                // Set password dan aktifkan akun; lakukan berurutan agar status pasti aktif
                $ok1 = $this->User_model->set_password_only($uid, $pass);
                $ok2 = $ok1 ? $this->User_model->activate_user($uid) : false;
                if ($ok1 && $ok2) {
                    $this->session->set_flashdata('success', 'Aktivasi berhasil. Silakan login.');
                    redirect('auth/login');
                    return;
                } else {
                    $data['error'] = 'Gagal menyimpan password.';
                    $data['user_id'] = $uid;
                    $mode = 'set_password';
                }
            }
        }

        $data['mode'] = $mode;
        $this->load->view('auth/activate', $data);
    }

    public function logout() {
        // Hapus session
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function forgot_password() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        $mode = $this->input->post('mode');
        $data = [];
        if (!$mode) { $mode = 'ask_phone'; }

        if ($mode === 'ask_phone') {
            if ($this->input->post()) {
                $this->form_validation->set_rules('phone', 'Nomor WhatsApp', 'required|trim');
                if ($this->form_validation->run() == FALSE) {
                    $data['error'] = validation_errors();
                } else {
                    $phone = $this->input->post('phone', TRUE);
                    $user = $this->User_model->get_user_by_phone($phone);
                    if ($user) {
                        // Generate kode reset password
                        $reset_code = sprintf("%06d", mt_rand(100000, 999999));
                        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                        
                        // Simpan kode reset password
                        $this->User_model->set_reset_code($user->id, $reset_code, $expiry);
                        
                        // Kirim kode via WhatsApp
                        $this->load->library('whatsapp_sender');
                        $appName = 'Sistem TTD Jasa/Bonus';
                        $msg = "Halo {$user->nama},\nKode reset password {$appName}: {$reset_code}.\nKode ini berlaku selama 1 jam.";
                        $res = $this->whatsapp_sender->send($phone, $msg);
                        
                        if (!$res['success']) {
                            $data['error'] = 'Gagal mengirim WhatsApp: ' . (is_string($res['body']) ? $res['body'] : '');
                            $data['phone'] = $phone;
                            $mode = 'ask_phone';
                        } else {
                            $data['phone'] = $phone;
                            $mode = 'verify_code';
                        }
                    } else {
                        $data['error'] = 'Nomor WhatsApp tidak terdaftar.';
                    }
                }
            }
        } elseif ($mode === 'verify_code') {
            $this->form_validation->set_rules('phone', 'Nomor WhatsApp', 'required|trim');
            $this->form_validation->set_rules('reset_code', 'Kode Reset', 'required|trim|regex_match[/^\d{6}$/]');
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
                $mode = 'verify_code';
            } else {
                $phone = $this->input->post('phone', TRUE);
                $code = $this->input->post('reset_code', TRUE);
                $user = $this->User_model->verify_reset_code_by_phone($phone, $code);
                if (!$user) {
                    $data['error'] = 'Kode salah atau kadaluarsa.';
                    $data['phone'] = $phone;
                    $mode = 'verify_code';
                } else {
                    $data['user_id'] = $user->id;
                    $mode = 'set_password';
                }
            }
        } elseif ($mode === 'set_password') {
            $this->form_validation->set_rules('user_id', 'User', 'required|integer');
            $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
                $data['user_id'] = $this->input->post('user_id');
                $mode = 'set_password';
            } else {
                $uid = (int)$this->input->post('user_id');
                $pass = $this->input->post('password', TRUE);
                // Set password baru
                $ok = $this->User_model->set_password_only($uid, $pass);
                // Hapus kode reset password
                $this->User_model->clear_reset_code($uid);
                
                if ($ok) {
                    $this->session->set_flashdata('success', 'Password berhasil diubah. Silakan login.');
                    redirect('auth/login');
                    return;
                } else {
                    $data['error'] = 'Gagal mengubah password.';
                    $data['user_id'] = $uid;
                    $mode = 'set_password';
                }
            }
        }

        $data['mode'] = $mode;
        $this->load->view('auth/forgot_password', $data);
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