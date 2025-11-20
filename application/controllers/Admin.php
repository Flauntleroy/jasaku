<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_DB_query_builder $db
 * @property User_model $User_model
 * @property Jasa_bonus_model $Jasa_bonus_model
 * @property Tanda_tangan_model $Tanda_tangan_model
 * @property Csv_export $csv_export
 * @property Whatsapp_sender $whatsapp_sender
 * @property Excel_export $excel_export
 */
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
            $loginUrl = base_url('auth/login');
            if (!headers_sent()) {
                redirect('auth/login');
                exit; // pastikan eksekusi berhenti setelah redirect
            } else {
                // Fallback bila header sudah terkirim: gunakan client-side redirect
                echo '<script>location.replace("'.$loginUrl.'");</script>';
                echo '<noscript><meta http-equiv="refresh" content="0;url='.$loginUrl.'"></noscript>';
                exit;
            }
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
        // Get charts data
        $data['monthly'] = $this->Jasa_bonus_model->get_monthly_sums(12);
        $data['signed_percent'] = $this->Jasa_bonus_model->get_signed_percentage();
        
        // Filter bulan untuk statistik Belum TTD (opsional)
        $unsigned_month = $this->input->get('unsigned_month'); // format YYYY-MM
        if (empty($unsigned_month)) { $unsigned_month = date('Y-m'); }
        $monthStart = $unsigned_month . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));
        $data['unsigned_month'] = $unsigned_month;
        $data['unsigned_month_label'] = date('M Y', strtotime($monthStart));
        $data['unsigned_count'] = $this->Jasa_bonus_model->count_unsigned_by_period($monthStart, $monthEnd);
        $data['unsigned_month_total'] = $this->Jasa_bonus_model->count_total_by_period($monthStart, $monthEnd);
        $data['unsigned_month_signed'] = $this->Jasa_bonus_model->count_signed_by_period($monthStart, $monthEnd);

        // Panel Top Pegawai tidak digunakan; baris dihapus untuk menghindari query tambahan

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
        
        // Handle GET export/template first
        $export = $this->input->get('export');
        $template = $this->input->get('template');
        if ($export === 'xlsx') { $this->export_jasa_bonus_xlsx(); return; }
        if ($template === 'xlsx') { $this->download_jasa_template_xlsx(); return; }
        
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
        // Handle print view
        if ($this->input->get('print')) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            // Default ke 3 bulan terakhir bila filter kosong untuk menghindari load data besar
            if (empty($start_date) && empty($end_date)) {
                $start_date = date('Y-m-01', strtotime('-3 months'));
                $end_date = date('Y-m-t');
            }
            $data['laporan'] = $this->Tanda_tangan_model->get_for_export($start_date, $end_date);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            // Render bare view (no template wrapper) for printing
            $this->load->view('admin/laporan_print', $data);
            return;
        }
        
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        // Default ke 3 bulan terakhir bila filter kosong untuk menghindari load data besar
        if (empty($start_date) && empty($end_date)) {
            $start_date = date('Y-m-01', strtotime('-3 months'));
            $end_date = date('Y-m-t');
        }
        
        $data['laporan'] = $this->Tanda_tangan_model->get_for_export($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
    $data['content'] = $this->load->view('admin/laporan', $data, TRUE);
    $this->load->view('template/main', $data);
    }

    /** Halaman dan aksi Mode Pemeliharaan (Maintenance) */
    public function maintenance() {
        $data['title'] = 'Maintenance';
        $data['page_title'] = 'Mode Pemeliharaan';

        $flagPath = FCPATH . 'maintenance.flag';
        $data['is_active'] = file_exists($flagPath);
        $data['maintenance'] = null;
        $data['remaining_secs'] = null;

        if ($this->input->post('action')) {
            $action = $this->input->post('action');
            if ($action === 'enable') {
                $duration = (int)$this->input->post('duration_minutes');
                if ($duration <= 0) { $duration = 30; }
                $reason = $this->input->post('reason', TRUE);
                $payload = array(
                    'created_at' => time(),
                    'end_at' => time() + ($duration * 60),
                    'reason' => $reason,
                    'enabled_by' => $this->session->userdata('username') ?: 'admin'
                );
                @file_put_contents($flagPath, json_encode($payload, JSON_PRETTY_PRINT));
                $this->session->set_flashdata('success', 'Maintenance diaktifkan selama ' . $duration . ' menit.');
                redirect('admin/maintenance');
                return;
            } elseif ($action === 'disable') {
                if (file_exists($flagPath)) { @unlink($flagPath); }
                $this->session->set_flashdata('success', 'Maintenance dinonaktifkan.');
                redirect('admin/maintenance');
                return;
            }
        }

        if ($data['is_active']) {
            $raw = @file_get_contents($flagPath);
            $json = json_decode($raw, TRUE);
            if (is_array($json)) { $data['maintenance'] = $json; }
            $endAt = isset($json['end_at']) ? (int)$json['end_at'] : null;
            if ($endAt) { $data['remaining_secs'] = max(0, $endAt - time()); }
        }

        $data['content'] = $this->load->view('admin/maintenance', $data, TRUE);
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
        } elseif ($action == 'generate_activation') {
            $this->generate_activation();
        } elseif ($action == 'import_csv') {
            $this->import_users_csv();
        } elseif ($action == 'send_activation_whatsapp') {
            $this->send_activation_whatsapp();
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
                'phone' => $this->input->post('phone', TRUE),
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
                'phone' => $this->input->post('phone', TRUE),
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

    private function generate_activation() {
        $id = $this->input->post('id');
        if (!$id) { redirect('admin/users'); return; }
        $code = $this->User_model->set_activation_code($id);
        if ($code) {
            // In a real app, send via SMS/Email. Here we just flash it.
            $this->session->set_flashdata('success', 'Kode aktivasi: ' . html_escape($code));
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat kode aktivasi.');
        }
        redirect('admin/users');
    }

    private function import_users_csv() {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'File CSV tidak valid.');
            redirect('admin/users');
            return;
        }
        $tmp = $_FILES['csv_file']['tmp_name'];
        $rows = [];
        if (($handle = fopen($tmp, 'r')) !== false) {
            $header = fgetcsv($handle);
            if ($header === false) { fclose($handle); $this->session->set_flashdata('error', 'CSV kosong.'); redirect('admin/users'); return; }
            $map = array_map('strtolower', $header);
            while (($data = fgetcsv($handle)) !== false) {
                $row = [];
                foreach ($data as $i => $val) {
                    $key = $map[$i] ?? ('col'.$i);
                    $row[$key] = $val;
                }
                $rows[] = $row;
            }
            fclose($handle);
        }
        $result = $this->User_model->batch_upsert_by_nik($rows);
        // Generate activation codes for all newly created/updated users that are inactive
        $affected = 0;
        foreach ($rows as $r) {
            if (empty($r['nik'])) continue;
            $this->db->where('nik', $r['nik']);
            $q = $this->db->get('users');
            if ($q->num_rows() === 1) {
                $u = $q->row();
                if ((int)$u->is_active === 0) { $this->User_model->set_activation_code($u->id); $affected++; }
            }
        }
        $msg = 'Import selesai. Dibuat: '.$result['created'].', diperbarui: '.$result['updated'].'.';
        if (!empty($result['errors'])) { $msg .= ' Error: '.count($result['errors']).'.'; }
        if ($affected > 0) { $msg .= ' Kode aktivasi dibuat: '.$affected.'.'; }
        $this->session->set_flashdata('success', $msg);
        if (!empty($result['errors'])) {
            $this->session->set_flashdata('error', implode("; ", array_slice($result['errors'], 0, 5)) . (count($result['errors'])>5?' ...':''));
        }
        redirect('admin/users');
    }

    private function send_activation_whatsapp() {
        $id = $this->input->post('id');
        if (!$id) { redirect('admin/users'); return; }
        $user = $this->User_model->get_user_by_id($id);
        if (!$user) { $this->session->set_flashdata('error', 'User tidak ditemukan.'); redirect('admin/users'); return; }
        if (empty($user->phone)) { $this->session->set_flashdata('error', 'Nomor HP kosong. Isi HP terlebih dahulu.'); redirect('admin/users'); return; }
        // Ensure activation code exists
        $code = $user->activation_code;
        if (empty($code) || (int)$user->is_active === 1) {
            $code = $this->User_model->set_activation_code($user->id);
        }
        if (!$code) { $this->session->set_flashdata('error', 'Gagal menyiapkan kode aktivasi.'); redirect('admin/users'); return; }

        $this->load->library('whatsapp_sender');
        $appName = 'Sistem TTD Jasa/Bonus';
        $activateUrl = base_url('activate');
        $msg = "Halo {$user->nama},\n".
               "Kode aktivasi akun {$appName} Anda: {$code}.\n".
               "Aktivasi di: {$activateUrl}\n".
               "Gunakan NIK dan kode ini untuk setel password.";
        $res = $this->whatsapp_sender->send($user->phone, $msg);
        if ($res['success']) {
            $this->session->set_flashdata('success', 'Kode aktivasi dikirim via WhatsApp.');
        } else {
            $this->session->set_flashdata('error', 'Gagal kirim WA: '.$res['body']);
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
        } elseif ($action == 'send_ttd_request') {
            $this->send_ttd_request();
        } elseif ($action == 'create_bulk') {
            $this->create_jasa_bonus_bulk();
        } elseif ($action == 'import_xlsx') {
            $this->import_jasa_bonus_xlsx();
        }
    }

    private function create_jasa_bonus() {
    $this->form_validation->set_rules('user_id', 'Pegawai', 'required');
        // Support either periode (date) or periode_month (YYYY-MM)
        if ($this->input->post('periode_month')) {
            $this->form_validation->set_rules('periode_month', 'Periode', 'required');
        } else {
            $this->form_validation->set_rules('periode', 'Periode', 'required');
        }
        $this->form_validation->set_rules('terima_sebelum_pajak', 'Terima Sebelum Pajak', 'required|numeric');
        $this->form_validation->set_rules('terima_setelah_pajak', 'Terima Setelah Pajak', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $user_id = $this->input->post('user_id');
            $user = $this->User_model->get_user_by_id($user_id);
            if (!$user || empty($user->nik)) { $this->session->set_flashdata('error', 'Pegawai/NIK tidak ditemukan.'); redirect('admin/jasa-bonus'); return; }
            $periode = $this->input->post('periode');
            $periode_month = $this->input->post('periode_month');
            if (!empty($periode_month) && preg_match('/^\d{4}-\d{2}$/', $periode_month)) {
                // Convert 2025-09 -> 2025-09-01
                $periode = $periode_month . '-01';
            }
            
            // Check if period already exists for this user
            if ($this->Jasa_bonus_model->period_exists_for_user($user_id, $periode)) {
                $this->session->set_flashdata('error', 'Data jasa/bonus untuk periode ini sudah ada untuk pegawai tersebut.');
            } else {
                $data = array(
                    'nik' => $user->nik,
                    'periode' => $periode,
                    'terima_sebelum_pajak' => $this->input->post('terima_sebelum_pajak'),
                    'pajak_5' => $this->input->post('pajak_5') ?: 0,
                    'pajak_15' => $this->input->post('pajak_15') ?: 0,
                    'pajak_0' => $this->input->post('pajak_0') ?: 0,
                    'terima_setelah_pajak' => $this->input->post('terima_setelah_pajak')
                );
                
                if ($this->Jasa_bonus_model->create_jasa_bonus($data)) {
                    $this->session->set_flashdata('success', 'Data jasa/bonus berhasil ditambahkan.');
                    // Optional: auto send WA request after create
                    if ($this->input->post('send_wa_after_create')) {
                        // find the just-created record id
                        $this->db->select('id');
                        $this->db->from('jasa_bonus');
                        $this->db->where('nik', $user->nik);
                        $this->db->where('periode', $periode);
                        $this->db->order_by('id', 'DESC');
                        $jb = $this->db->get()->row();
                        if ($jb && isset($jb->id)) {
                            $_POST['id'] = $jb->id; // set for internal call
                            $this->send_ttd_request();
                            return; // send_ttd_request already redirects
                        }
                    }
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

    /** Kirim permintaan TTD ke pegawai via WhatsApp untuk satu entri jasa/bonus */
    private function send_ttd_request() {
        $id = $this->input->post('id');
        if (!$id) { redirect('admin/jasa-bonus'); return; }

        $jb = $this->Jasa_bonus_model->get_jasa_bonus_by_id($id);
        if (!$jb) { $this->session->set_flashdata('error', 'Data tidak ditemukan.'); redirect('admin/jasa-bonus'); return; }

        // Sudah ditandatangani?
        if ($this->Tanda_tangan_model->is_already_signed($id)) {
            $this->session->set_flashdata('error', 'Dokumen ini sudah ditandatangani.');
            redirect('admin/jasa-bonus'); return;
        }

    // Ambil user untuk nomor HP
    $user = $this->User_model->get_user_by_nik($jb->nik);
        if (!$user) { $this->session->set_flashdata('error', 'Pegawai tidak ditemukan.'); redirect('admin/jasa-bonus'); return; }
        if (empty($user->phone)) { $this->session->set_flashdata('error', 'Nomor HP pegawai kosong. Lengkapi dulu di menu Pegawai.'); redirect('admin/jasa-bonus'); return; }

        // Kirim WA
        $this->load->library('whatsapp_sender');
        $periode = date('F Y', strtotime($jb->periode));
        $loginUrl = base_url('auth/login');
        $dashboardUrl = base_url('pegawai/dashboard');
        $msg = "Halo {$user->nama},\n".
               "Terdapat dokumen Jasa/Bonus periode {$periode} yang perlu ditandatangani.\n".
               "Rincian: Setelah pajak Rp ".number_format((float)$jb->terima_setelah_pajak, 0, ',', '.')."\n".
               "Silakan login: {$loginUrl} lalu buka Dashboard: {$dashboardUrl} untuk menandatangani.";
        $res = $this->whatsapp_sender->send($user->phone, $msg);
        if ($res['success']) {
            $this->session->set_flashdata('success', 'Permintaan TTD dikirim via WhatsApp.');
        } else {
            $this->session->set_flashdata('error', 'Gagal kirim permintaan TTD: '.$res['body']);
        }
        redirect('admin/jasa-bonus');
    }

    /** Tambah jasa/bonus untuk banyak pegawai sekaligus */
    private function create_jasa_bonus_bulk() {
    $user_ids = $this->input->post('user_ids'); // array
        $periode = $this->input->post('periode');
        $terima_sebelum_pajak = $this->input->post('terima_sebelum_pajak');
        $pajak_5 = $this->input->post('pajak_5') ?: 0;
        $pajak_15 = $this->input->post('pajak_15') ?: 0;
        $pajak_0 = $this->input->post('pajak_0') ?: 0;
        $terima_setelah_pajak = $this->input->post('terima_setelah_pajak');

        if (!is_array($user_ids) || empty($user_ids)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu pegawai.');
            redirect('admin/jasa-bonus'); return;
        }
        if (empty($periode) || empty($terima_sebelum_pajak) || empty($terima_setelah_pajak)) {
            $this->session->set_flashdata('error', 'Lengkapi periode dan nominal.');
            redirect('admin/jasa-bonus'); return;
        }

        $created = 0; $skipped = 0;
        foreach ($user_ids as $uid) {
            $u = $this->User_model->get_user_by_id($uid);
            if (!$u || empty($u->nik)) { $skipped++; continue; }
            if ($this->Jasa_bonus_model->period_exists_for_nik($u->nik, $periode)) { $skipped++; continue; }
            $data = array(
                'nik' => $u->nik,
                'periode' => $periode,
                'terima_sebelum_pajak' => $terima_sebelum_pajak,
                'pajak_5' => $pajak_5,
                'pajak_15' => $pajak_15,
                'pajak_0' => $pajak_0,
                'terima_setelah_pajak' => $terima_setelah_pajak
            );
            if ($this->Jasa_bonus_model->create_jasa_bonus($data)) { $created++; }
        }

        $msg = "Tambah massal selesai. Dibuat: {$created}. Dilewati (sudah ada): {$skipped}.";
        $this->session->set_flashdata('success', $msg);
        redirect('admin/jasa-bonus');
    }

    private function export_laporan() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $laporan = $this->Tanda_tangan_model->get_for_export($start_date, $end_date);
        
        // Export to XLSX including signature image
        $this->load->library('excel_export');
        $this->excel_export->export_laporan($laporan, $start_date, $end_date);
    }

    /** Export semua data jasa/bonus ke Excel sesuai format yang diminta
     *  @property Excel_export $excel_export
     */
    private function export_jasa_bonus_xlsx() {
        $rows = $this->Jasa_bonus_model->get_jasa_bonus_with_signature();
        $periodeTitle = 'Export Jasa/Bonus - dibuat '.date('d M Y H:i');
        $this->load->library('excel_export');
        $this->excel_export->export_jasa_bonus($rows, $periodeTitle);
    }

    /** Download template kosong untuk diisi (format Excel) */
    private function download_jasa_template_xlsx() {
        $rows = [];
        $periodeTitle = 'Template Import Jasa/Bonus (isi baris mulai di bawah header)';
        $this->load->library('excel_export');
        $this->excel_export->export_jasa_bonus($rows, $periodeTitle);
    }

    /** Import data jasa/bonus dari file Excel sesuai template */
    private function import_jasa_bonus_xlsx() {
        // Perlu periode (bulan)
        $periode_month = $this->input->post('periode_month');
        if (empty($periode_month) || !preg_match('/^\d{4}-\d{2}$/', $periode_month)) {
            $this->session->set_flashdata('error', 'Periode (bulan) wajib diisi.');
            redirect('admin/jasa-bonus'); return;
        }
        $periode = $periode_month.'-01';

        if (!isset($_FILES['xlsx_file']) || $_FILES['xlsx_file']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'File Excel tidak valid.');
            redirect('admin/jasa-bonus'); return;
        }

        $tmp = $_FILES['xlsx_file']['tmp_name'];
        try {
            // Baca file menggunakan PhpSpreadsheet
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmp);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($tmp);
            $sheet = $spreadsheet->getActiveSheet();

            // Deteksi baris header teratas (kolom A berisi 'No')
            $headerTop = 1;
            for ($r=1; $r<=10; $r++) {
                $val = trim((string)$sheet->getCell('A'.$r)->getFormattedValue());
                if (strcasecmp($val, 'No') === 0) { $headerTop = $r; break; }
            }
            $dataStart = $headerTop + 2; // header 2 baris

            $lastRow = $sheet->getHighestRow();
            $entries = [];
            for ($row = $dataStart; $row <= $lastRow; $row++) {
                $nama = trim((string)$sheet->getCell('B'.$row)->getFormattedValue());
                $nik  = trim((string)$sheet->getCell('E'.$row)->getFormattedValue());
                // Stop bila baris kosong
                if ($nama === '' && $nik === '') { continue; }

                $ruangan = trim((string)$sheet->getCell('C'.$row)->getFormattedValue());
                $asn = trim((string)$sheet->getCell('D'.$row)->getFormattedValue());
                $status_ptkp = trim((string)$sheet->getCell('F'.$row)->getFormattedValue());
                $golongan = trim((string)$sheet->getCell('G'.$row)->getFormattedValue());

                $bruto = (float)str_replace([','], [''], (string)$sheet->getCell('H'.$row)->getCalculatedValue());
                $p5    = (float)str_replace([','], [''], (string)$sheet->getCell('I'.$row)->getCalculatedValue());
                $p15   = (float)str_replace([','], [''], (string)$sheet->getCell('J'.$row)->getCalculatedValue());
                $p0    = (float)str_replace([','], [''], (string)$sheet->getCell('K'.$row)->getCalculatedValue());
                $nettoCell = $sheet->getCell('L'.$row);
                $netto = $nettoCell->isFormula() ? (float)$nettoCell->getCalculatedValue() : (float)str_replace([','], [''], (string)$nettoCell->getValue());
                if ($netto <= 0) { $netto = max(0, $bruto - ($p5 + $p15 + $p0)); }

                $entries[] = compact('nik','nama','ruangan','asn','status_ptkp','golongan','bruto','p5','p15','p0','netto');
            }

            if (empty($entries)) {
                $this->session->set_flashdata('error', 'Tidak ada baris data yang terbaca dari file.');
                redirect('admin/jasa-bonus'); return;
            }

            // Upsert pegawai berdasarkan NIK terlebih dulu
            $userRows = [];
            foreach ($entries as $e) {
                $userRows[] = [
                    'nik' => $e['nik'],
                    'nama' => $e['nama'],
                    'ruangan' => $e['ruangan'],
                    'asn' => $e['asn'],
                    'status_ptkp' => $e['status_ptkp'],
                    'golongan' => $e['golongan'],
                ];
            }
            // Hapus duplikasi NIK untuk efisiensi
            $seen = [];$uniqueUserRows = [];
            foreach ($userRows as $ur) { if (!isset($seen[$ur['nik']])) { $uniqueUserRows[] = $ur; $seen[$ur['nik']] = true; } }
            $this->User_model->batch_upsert_by_nik($uniqueUserRows);

            // Insert/update jasa_bonus per baris
            $created = 0; $updated = 0; $skipped = 0; $errors = 0;
            foreach ($entries as $e) {
                if (empty($e['nik'])) { $errors++; continue; }
                $user = $this->User_model->get_user_by_nik($e['nik']);
                if (!$user) { $errors++; continue; }

                $data = array(
                    'terima_sebelum_pajak' => $e['bruto'],
                    'pajak_5' => $e['p5'],
                    'pajak_15' => $e['p15'],
                    'pajak_0' => $e['p0'],
                    'terima_setelah_pajak' => $e['netto'],
                );

                // Ada data periode ini?
                if ($this->Jasa_bonus_model->period_exists_for_nik($user->nik, $periode)) {
                    // Update
                    $this->db->where('nik', $user->nik);
                    $this->db->where('periode', $periode);
                    if ($this->db->update('jasa_bonus', $data)) { $updated++; } else { $errors++; }
                } else {
                    $data['nik'] = $user->nik; $data['periode'] = $periode;
                    if ($this->db->insert('jasa_bonus', $data)) { $created++; } else { $errors++; }
                }
            }

            $msg = "Import XLSX selesai. Dibuat: {$created}, diperbarui: {$updated}.";
            if ($errors) { $msg .= " Error: {$errors}."; }
            $this->session->set_flashdata('success', $msg);
        } catch (\Throwable $e) {
            $this->session->set_flashdata('error', 'Gagal memproses file Excel: '.$e->getMessage());
        }
        redirect('admin/jasa-bonus');
    }
}