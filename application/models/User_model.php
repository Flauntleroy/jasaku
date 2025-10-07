<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Login user
    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verify password - assume password is hashed with password_hash()
            if (!password_verify($password, $user->password_hash)) {
                return FALSE;
            }
            // Enforce activation for pegawai (admin can always login)
            if ($user->role !== 'admin' && (int)$user->is_active !== 1) {
                // Return a marker object or FALSE; we'll handle in controller by fetching user
                return (object) array('pending_activation' => true, 'id' => $user->id, 'username' => $user->username, 'nama' => $user->nama, 'role' => $user->role);
            }
            // Update last_login
            $this->db->where('id', $user->id)->update('users', array('last_login' => date('Y-m-d H:i:s')));
            return $user;
        }
        return FALSE;
    }

    // Get user by ID
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get user by NIK
    public function get_user_by_nik($nik) {
        $this->db->where('nik', $nik);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get all users
    public function get_all_users() {
        $query = $this->db->get('users');
        return $query->result();
    }

    // Get user by phone
    public function get_user_by_phone($phone) {
        $this->db->where('phone', $phone);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Create new user
    public function create_user($data) {
        // Hash password
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        // Default inactive for pegawai if not explicitly active
        if (!isset($data['is_active'])) {
            $data['is_active'] = ($data['role'] ?? 'pegawai') === 'admin' ? 1 : 0;
        }
        
        return $this->db->insert('users', $data);
    }

    // Update user
    public function update_user($id, $data) {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        // Filter only columns that actually exist in the users table to avoid SQL errors
        // (e.g., when a new field like no_rekening hasn't been migrated yet)
        $payload = [];
        try {
            $fields = $this->db->list_fields('users');
        } catch (Exception $e) {
            // Fallback: if list_fields fails, proceed with raw data (best effort)
            $fields = [];
        }
        if (!empty($fields)) {
            $allowed = array_flip($fields);
            // never allow updating primary key via payload
            unset($allowed['id']);
            foreach ($data as $k => $v) {
                if (isset($allowed[$k])) { $payload[$k] = $v; }
            }
        } else {
            // If we couldn't detect fields, at least avoid obvious problematic keys
            $payload = $data;
            unset($payload['id']);
        }

        // Nothing to update? Consider it success.
        if (empty($payload)) { return TRUE; }

        $this->db->where('id', $id);
        $ok = $this->db->update('users', $payload);
        if (!$ok) {
            $err = $this->db->error();
            log_message('error', 'User_model::update_user failed for id '.$id.' - '.$err['code'].': '.$err['message']);
        }
        return $ok;
    }

    // Delete user
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // Get users by role
    public function get_users_by_role($role) {
        $this->db->where('role', $role);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Check if username exists
    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    // Check if NIK exists
    public function nik_exists($nik, $exclude_id = null) {
        $this->db->where('nik', $nik);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    // Generate and set activation code for a user id
    public function set_activation_code($user_id, $code = null) {
        if ($code === null) {
            // Generate 6-digit numeric OTP (000000 - 999999)
            try {
                $num = random_int(0, 999999);
            } catch (Exception $e) {
                // Fallback to mt_rand if random_int unavailable
                $num = mt_rand(0, 999999);
            }
            $code = str_pad((string)$num, 6, '0', STR_PAD_LEFT);
        }
        $this->db->where('id', $user_id);
        $ok = $this->db->update('users', array('activation_code' => $code, 'is_active' => 0));
        return $ok ? $code : false;
    }
    
    // Set reset password code
    public function set_reset_code($user_id, $reset_code, $expiry) {
        $data = array(
            'reset_code' => $reset_code,
            'reset_expiry' => $expiry
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
    
    // Verify reset code by phone
    public function verify_reset_code_by_phone($phone, $code) {
        $this->db->where('phone', $phone);
        $this->db->where('reset_code', $code);
        $this->db->where('reset_expiry >', date('Y-m-d H:i:s'));
        $query = $this->db->get('users');
        return $query->row();
    }
    
    // Clear reset code
    public function clear_reset_code($user_id) {
        $data = array(
            'reset_code' => NULL,
            'reset_expiry' => NULL
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    // Activate user with NIK and activation code, set password
    public function activate_by_nik($nik, $activation_code, $new_password) {
        $this->db->where('nik', $nik);
        $this->db->where('activation_code', $activation_code);
        $query = $this->db->get('users');
        if ($query->num_rows() !== 1) return false;
        $user = $query->row();
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->where('id', $user->id);
        return $this->db->update('users', array(
            'password_hash' => $hash,
            'is_active' => 1,
            'activation_code' => null,
            'activated_at' => date('Y-m-d H:i:s')
        ));
    }

    // Verify activation code by phone
    public function verify_activation_code_by_phone($phone, $activation_code) {
        $this->db->where('phone', $phone);
        $this->db->where('activation_code', $activation_code);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Verify activation code by NIK
    public function verify_activation_code_by_nik($nik, $activation_code) {
        $this->db->where('nik', $nik);
        $this->db->where('activation_code', $activation_code);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Activate user by id (without changing password)
    public function activate_user($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', array(
            'is_active' => 1,
            'activation_code' => null,
            'activated_at' => date('Y-m-d H:i:s')
        ));
    }

    // Set password only
    public function set_password_only($user_id, $new_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->where('id', $user_id);
        return $this->db->update('users', array('password_hash' => $hash));
    }

    // Batch create or upsert stub users from array of rows; each row minimally: nik, nama
    public function batch_upsert_by_nik($rows) {
        $created = 0; $updated = 0; $errors = [];
        foreach ($rows as $i => $r) {
            $nik = trim($r['nik'] ?? '');
            $nama = trim($r['nama'] ?? '');
            if ($nik === '' || $nama === '') { $errors[] = "Baris ".($i+1).": NIK/Nama kosong"; continue; }
            // Try to find existing by NIK
            $this->db->where('nik', $nik);
            $q = $this->db->get('users');
            $payload = array(
                'nama' => $nama,
                'ruangan' => $r['ruangan'] ?? null,
                'asn' => $r['asn'] ?? 'Tidak',
                'status_ptkp' => $r['status_ptkp'] ?? null,
                'golongan' => $r['golongan'] ?? null,
                'nik' => $nik,
                'role' => 'pegawai',
                'username' => $r['username'] ?? $nik,
                'phone' => $r['phone'] ?? null,
                'is_active' => 0,
            );
            if ($q->num_rows() === 0) {
                // Set temp password to random (won't be used until activation)
                $payload['password_hash'] = password_hash(bin2hex(random_bytes(6)), PASSWORD_DEFAULT);
                if ($this->db->insert('users', $payload)) { $created++; } else { $errors[] = "Baris ".($i+1).": gagal insert"; }
            } else {
                $user = $q->row();
                // Don't overwrite username if exists
                unset($payload['username']);
                $this->db->where('id', $user->id);
                if ($this->db->update('users', $payload)) { $updated++; } else { $errors[] = "Baris ".($i+1).": gagal update"; }
            }
        }
        return compact('created','updated','errors');
    }
}