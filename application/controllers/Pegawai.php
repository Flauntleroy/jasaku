<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 * @property User_model $User_model
 * @property Jasa_bonus_model $Jasa_bonus_model
 * @property Tanda_tangan_model $Tanda_tangan_model
 */
class Pegawai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    $this->load->model('User_model');
        $this->load->model('Jasa_bonus_model');
        $this->load->model('Tanda_tangan_model');
        $this->load->library('form_validation');
    $this->load->library('session');

        $this->check_pegawai_access();
    }

    private function check_pegawai_access() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'pegawai') {
            show_404();
        }
    }

    public function dashboard() {
        $data['title'] = 'Dashboard Pegawai';
        $data['page_title'] = 'Dashboard Pegawai';

        $user_id = $this->session->userdata('user_id');
        $jasa_list = $this->Jasa_bonus_model->get_jasa_bonus_by_user($user_id);

        $current = null;
        if (!empty($jasa_list)) {
            // Ambil entri terbaru (sudah di-order DESC oleh model)
            $current = $jasa_list[0];
            // Cek status TTD
            $ttd = $this->Tanda_tangan_model->get_by_jasa_bonus_id($current->id);
            $current->signed = $ttd ? true : false;
            $current->signed_at = $ttd ? $ttd->signed_at : null;
            $current->tanda_tangan_image = $ttd ? $ttd->tanda_tangan_image : null;
        }

    $data['current_jasa'] = $current;
    // Small history preview (latest 6 items) – always show on dashboard
    $history_all = $this->Jasa_bonus_model->get_user_jasa_with_signature_filtered($user_id, null, null);
    $data['history_preview'] = is_array($history_all) ? array_slice($history_all, 0, 6) : array();

    // KPIs for Pegawai (YTD)
    $data['ytd_netto'] = $this->Jasa_bonus_model->get_user_year_to_date_netto($user_id);
    $data['ytd_pajak'] = $this->Jasa_bonus_model->get_user_year_to_date_pajak_total($user_id);
    $data['ytd_avg'] = $this->Jasa_bonus_model->get_user_year_to_date_netto_avg($user_id);
    $data['ytd_signed_percent'] = $this->Jasa_bonus_model->get_user_year_to_date_signed_percent($user_id);

    // Unsigned list (for banner / call-to-action)
    $all = $this->Jasa_bonus_model->get_user_jasa_with_signature_filtered($user_id, date('Y'), 'unsigned');
    $data['unsigned_list'] = $all;

    // Chart data for the whole current year (netto per month)
    $year = (int)date('Y');
    $line = $this->Jasa_bonus_model->get_user_monthly_netto_for_year($user_id, $year);
    $cats = isset($line['categories']) && is_array($line['categories']) ? $line['categories'] : [];
    $net = isset($line['netto']) && is_array($line['netto']) ? $line['netto'] : [];
    $lineTitle = 'Perolehan Jasa Tahun Ini';
    // Fallback: if empty (no data in current calendar year), use last 12 months rolling window
    if (empty($cats) || empty($net)) {
        $ms = $this->Jasa_bonus_model->get_monthly_sums_for_user($user_id, 12);
        $cats = $ms['categories'] ?? [];
        $net = $ms['netto'] ?? [];
        $lineTitle = 'Perolehan Jasa 12 Bulan Terakhir';
    }
    $data['line_categories'] = $cats;
    $data['line_netto'] = $net;
    $data['line_title'] = $lineTitle;

        $data['content'] = $this->load->view('pegawai/dashboard', $data, TRUE);
        $this->load->view('template/main', $data);
    }

    public function history() {
        $data['title'] = 'Riwayat TTD';
        $data['page_title'] = 'Riwayat TTD';
        $user_id = $this->session->userdata('user_id');
        $year = $this->input->get('year');
        $status = $this->input->get('status'); // 'signed' | 'unsigned' | ''

        $data['years'] = $this->Jasa_bonus_model->get_years_for_user($user_id);
        $data['selected_year'] = $year;
        $data['selected_status'] = $status;
        $data['rows'] = $this->Jasa_bonus_model->get_user_jasa_with_signature_filtered($user_id, $year ?: null, $status ?: null);

        $data['content'] = $this->load->view('pegawai/history', $data, TRUE);
        $this->load->view('template/main', $data);
    }

    public function sign() {
        // Validasi method
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $jasa_bonus_id = $this->input->post('jasa_bonus_id');
    $image_data_url = $this->input->post('signature');
    $consent = $this->input->post('consent');

        if (empty($jasa_bonus_id) || empty($image_data_url)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap.');
            redirect('pegawai/tanda-tangan');
            return;
        }
        if (empty($consent)) {
            $this->session->set_flashdata('error', 'Anda harus menyetujui pernyataan sebelum menandatangani.');
            redirect('pegawai/tanda-tangan');
            return;
        }

        // Pastikan jasa ini milik user
        $jasa = $this->Jasa_bonus_model->get_jasa_bonus_by_id($jasa_bonus_id);
        $me = $this->User_model->get_user_by_id($user_id);
        if (!$jasa || !$me || $jasa->nik !== $me->nik) {
            show_404();
        }

        // Cek sudah ditandatangani
        if ($this->Tanda_tangan_model->is_already_signed($jasa_bonus_id)) {
            $this->session->set_flashdata('error', 'Dokumen sudah ditandatangani.');
            redirect('pegawai/tanda-tangan');
            return;
        }

        // Simpan gambar signature dari data URL
        $path = FCPATH . 'assets/images/signatures/';
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        $image_path_rel = null;
        if (preg_match('/^data:image\/(png|jpeg);base64,/', $image_data_url, $matches)) {
            $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
            $data_part = substr($image_data_url, strpos($image_data_url, ',') + 1);
            $decoded = base64_decode($data_part);
            if ($decoded === false) {
                $this->session->set_flashdata('error', 'Gagal memproses tanda tangan.');
                redirect('pegawai/tanda-tangan');
                return;
            }
            $filename = 'ttd_user_' . $user_id . '_jb_' . $jasa_bonus_id . '_' . time() . '.' . $ext;
            $fullpath = $path . $filename;
            if (file_put_contents($fullpath, $decoded) === false) {
                $this->session->set_flashdata('error', 'Gagal menyimpan tanda tangan.');
                redirect('pegawai/tanda-tangan');
                return;
            }
            // Server-side normalization: resample to fixed size 600x220 with white background
            $normalizedW = 600;
            $normalizedH = 220;
            $src = null;
            $mime = $matches[1];
            if (function_exists('imagecreatetruecolor')) {
                // Load source image depending on mime
                if ($mime === 'png') {
                    $src = @imagecreatefrompng($fullpath);
                } elseif ($mime === 'jpeg' || $mime === 'jpg') {
                    $src = @imagecreatefromjpeg($fullpath);
                }
                if ($src) {
                    $srcW = imagesx($src);
                    $srcH = imagesy($src);
                    $dst = imagecreatetruecolor($normalizedW, $normalizedH);
                    // Fill white background
                    $white = imagecolorallocate($dst, 255, 255, 255);
                    imagefilledrectangle($dst, 0, 0, $normalizedW, $normalizedH, $white);
                    // Aspect-fit scale with letterboxing
                    $scale = min($normalizedW / max($srcW, 1), $normalizedH / max($srcH, 1));
                    $dstW = (int) floor($srcW * $scale);
                    $dstH = (int) floor($srcH * $scale);
                    $dstX = (int) floor(($normalizedW - $dstW) / 2);
                    $dstY = (int) floor(($normalizedH - $dstH) / 2);
                    imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
                    // Always save as PNG to standardize format
                    imagepng($dst, $fullpath);
                    imagedestroy($dst);
                    imagedestroy($src);
                    // Ensure extension in path remains consistent (we saved PNG regardless)
                    if ($ext !== 'png') {
                        // Rename to .png
                        $newFilename = preg_replace('/\.(jpe?g)$/i', '.png', $filename);
                        $newFullpath = $path . $newFilename;
                        // If rename fails, keep original name but content is PNG
                        @rename($fullpath, $newFullpath);
                        if (file_exists($newFullpath)) {
                            $filename = $newFilename;
                            $fullpath = $newFullpath;
                        }
                    }
                }
            }
            $image_path_rel = 'assets/images/signatures/' . $filename;
        } else {
            $this->session->set_flashdata('error', 'Format tanda tangan tidak valid.');
            redirect('pegawai/dashboard');
            return;
        }

        // Simpan ke database
        $data_insert = array(
            'jasa_bonus_id' => $jasa_bonus_id,
            'nik' => $me->nik,
            'signed_at' => date('Y-m-d H:i:s'),
            'tanda_tangan_image' => $image_path_rel,
        );

        if ($this->Tanda_tangan_model->create_tanda_tangan($data_insert)) {
            $this->session->set_flashdata('success', 'Tanda tangan berhasil disimpan.');
            // Redirect ke halaman review tanda tangan
            redirect('pegawai/review-tanda-tangan/' . $jasa_bonus_id);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan tanda tangan ke database.');
            redirect('pegawai/tanda-tangan');
        }
    }
    
    /** Halaman review tanda tangan */
    public function review_tanda_tangan($jasa_bonus_id = null) {
        if (empty($jasa_bonus_id)) {
            redirect('pegawai/dashboard');
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $me = $this->User_model->get_user_by_id($user_id);
        
        // Ambil data jasa bonus
        $jasa = $this->Jasa_bonus_model->get_jasa_bonus_by_id($jasa_bonus_id);
        if (!$jasa || !$me || $jasa->nik !== $me->nik) {
            show_404();
        }
        
        // Ambil data tanda tangan
        $tanda_tangan = $this->Tanda_tangan_model->get_by_jasa_bonus_id($jasa_bonus_id);
        if (!$tanda_tangan) {
            $this->session->set_flashdata('error', 'Data tanda tangan tidak ditemukan.');
            redirect('pegawai/dashboard');
            return;
        }
        
        $data['title'] = 'Review Tanda Tangan';
        $data['page_title'] = 'Review Tanda Tangan';
        $data['jasa'] = $jasa;
        $data['tanda_tangan'] = $tanda_tangan;
        $data['content'] = $this->load->view('pegawai/review_signature', $data, TRUE);
        $this->load->view('template/main', $data);
    }
    
    /** Fungsi untuk tanda tangan ulang */
    public function tanda_tangan_ulang($jasa_bonus_id = null) {
        if (empty($jasa_bonus_id)) {
            redirect('pegawai/dashboard');
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $me = $this->User_model->get_user_by_id($user_id);
        
        // Ambil data jasa bonus
        $jasa = $this->Jasa_bonus_model->get_jasa_bonus_by_id($jasa_bonus_id);
        if (!$jasa || !$me || $jasa->nik !== $me->nik) {
            show_404();
        }
        
        // Hapus tanda tangan lama
        $this->Tanda_tangan_model->delete_by_jasa_bonus_id($jasa_bonus_id);
        
        // Redirect ke halaman tanda tangan dengan ID jasa bonus
        redirect('pegawai/tanda-tangan?id=' . $jasa_bonus_id);
    }

    /** Halaman khusus untuk tanda tangan */
    public function tanda_tangan() {
        $data['title'] = 'Tanda Tangan Dokumen';
        $data['page_title'] = 'Tanda Tangan Dokumen';

        $user_id = $this->session->userdata('user_id');
        $year = $this->input->get('year');
        $selected_id = $this->input->get('id');

        // Ambil daftar dokumen yang belum TTD (filter tahun opsional)
        $unsigned = $this->Jasa_bonus_model->get_user_jasa_with_signature_filtered($user_id, $year ?: null, 'unsigned');
        $data['unsigned_list'] = $unsigned;
        $data['selected_year'] = $year;

        // Tentukan dokumen yang dipilih untuk ditandatangani
        $selected = null;
        if (!empty($unsigned)) {
            if (!empty($selected_id)) {
                foreach ($unsigned as $row) {
                    if ((string)$row->id === (string)$selected_id) { $selected = $row; break; }
                }
            }
            if (!$selected) { $selected = $unsigned[0]; }
        }
        $data['current_jasa'] = $selected;

        $data['content'] = $this->load->view('pegawai/sign', $data, TRUE);
        $this->load->view('template/main', $data);
    }

    /** Halaman Profil Pegawai */
    public function profil() {
        $data['title'] = 'Profil Saya';
        $data['page_title'] = 'Profil Saya';
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        if (!$data['user']) { show_404(); }
        $data['content'] = $this->load->view('pegawai/profile', $data, TRUE);
        $this->load->view('template/main', $data);
    }

    /** Simpan perubahan profil (tanpa password) */
    public function profil_simpan() {
        if ($this->input->method() !== 'post') { show_404(); }
        $user_id = $this->session->userdata('user_id');
        $me = $this->User_model->get_user_by_id($user_id);
        if (!$me) { show_404(); }
        // Ambil field yang aman untuk diubah oleh pegawai
        $data = array(
            'nama' => trim($this->input->post('nama')),
            'username' => trim($this->input->post('username')),
            'phone' => trim($this->input->post('phone')),
            'ruangan' => trim($this->input->post('ruangan')),
            'no_rekening' => trim($this->input->post('no_rekening')),
            'asn' => trim($this->input->post('asn')),
            'status_ptkp' => trim($this->input->post('status_ptkp')),
            'golongan' => trim($this->input->post('golongan')),
        );
        // Validasi & penanganan username: jika kosong, jangan ubah; jika diisi & berubah, cek unik
        if ($data['username'] === '' || $data['username'] === null) {
            unset($data['username']);
        } else {
            if ($data['username'] !== $me->username && $this->User_model->username_exists($data['username'], $user_id)) {
                $this->session->set_flashdata('error', 'Username sudah digunakan.'); redirect('pegawai/profil'); return;
            }
        }
        // Normalisasi field opsional: kosong -> NULL agar lebih bersih di DB
        foreach (['phone','ruangan','no_rekening','status_ptkp','golongan'] as $opt) { if (isset($data[$opt]) && $data[$opt] === '') { $data[$opt] = null; } }
        // Normalisasi ASN ke 'Ya'/'Tidak'
        if (isset($data['asn'])) {
            // Normalisasi input agar case-insensitive
            $asn = strtolower(trim((string)$data['asn']));

            // Daftar pilihan yang diperbolehkan
            $allowed = [
                'pns'            => 'PNS',
                'pppk'           => 'PPPK',
                'honorer'        => 'Honorer',
                'pegawai kontrak'=> 'Pegawai Kontrak'
            ];

            if (isset($allowed[$asn])) {
                // Ubah ke format yang benar (title case)
                $data['asn'] = $allowed[$asn];
            } else {
                // Jika tidak cocok, tetap gunakan nilai lama
                $data['asn'] = $me->asn;
            }
        }
        // NIK tidak diizinkan diubah oleh pegawai untuk integritas
        // Simpan
        if ($this->User_model->update_user($user_id, $data)) {
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
        } else {
            // Tambahkan detail error DB untuk membantu debug saat pengembangan
            $err = $this->db->error();
            $msg = 'Gagal menyimpan profil.';
            if (!empty($err['code'])) { $msg .= ' ('.$err['code'].')'; }
            if (!empty($err['message'])) { $msg .= ' - '.$err['message']; }
            $this->session->set_flashdata('error', $msg);
        }
        redirect('pegawai/profil');
    }

    /** Ubah password */
    public function profil_ubah_password() {
        if ($this->input->method() !== 'post') { show_404(); }
        $user_id = $this->session->userdata('user_id');
        $me = $this->User_model->get_user_by_id($user_id);
        if (!$me) { show_404(); }
        $current = (string)$this->input->post('password_lama');
        $new = (string)$this->input->post('password_baru');
        $new2 = (string)$this->input->post('password_baru_konfirmasi');
        if ($new === '' || $new2 === '') {
            $this->session->set_flashdata('error', 'Password baru dan konfirmasi wajib diisi.'); redirect('pegawai/profil'); return;
        }
        if ($new !== $new2) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak sama.'); redirect('pegawai/profil'); return;
        }
        // Verifikasi password lama
        if (!password_verify($current, $me->password_hash)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai.'); redirect('pegawai/profil'); return;
        }
        // Set password
        if ($this->User_model->set_password_only($user_id, $new)) {
            $this->session->set_flashdata('success', 'Password berhasil diubah.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password.');
        }
        redirect('pegawai/profil');
    }
}
