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
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return FALSE;
    }

    // Get user by ID
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get all users
    public function get_all_users() {
        $query = $this->db->get('users');
        return $query->result();
    }

    // Create new user
    public function create_user($data) {
        // Hash password
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
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
        
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
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
}