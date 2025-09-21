<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jasa_bonus_model');
    }

    public function seed_data() {
        // Create admin user
        $admin_data = array(
            'nama' => 'Administrator',
            'ruangan' => 'IT',
            'asn' => 'Ya',
            'nik' => '1234567890123456',
            'status_ptkp' => 'TK/0',
            'golongan' => 'III/c',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin'
        );
        
        // Check if admin already exists
        if (!$this->User_model->username_exists('admin')) {
            $this->User_model->create_user($admin_data);
            echo "Admin user created.<br>";
        } else {
            echo "Admin user already exists.<br>";
        }

        // Create sample pegawai
        $pegawai_data = array(
            array(
                'nama' => 'John Doe',
                'ruangan' => 'Keuangan',
                'asn' => 'Ya',
                'nik' => '1234567890123457',
                'status_ptkp' => 'K/1',
                'golongan' => 'III/a',
                'username' => 'johndoe',
                'password' => 'pegawai123',
                'role' => 'pegawai'
            ),
            array(
                'nama' => 'Jane Smith',
                'ruangan' => 'SDM',
                'asn' => 'Ya',
                'nik' => '1234567890123458',
                'status_ptkp' => 'K/0',
                'golongan' => 'III/b',
                'username' => 'janesmith',
                'password' => 'pegawai123',
                'role' => 'pegawai'
            ),
            array(
                'nama' => 'Bob Wilson',
                'ruangan' => 'Operasional',
                'asn' => 'Tidak',
                'nik' => '1234567890123459',
                'status_ptkp' => 'TK/0',
                'golongan' => null,
                'username' => 'bobwilson',
                'password' => 'pegawai123',
                'role' => 'pegawai'
            )
        );

        foreach ($pegawai_data as $pegawai) {
            if (!$this->User_model->username_exists($pegawai['username'])) {
                $this->User_model->create_user($pegawai);
                echo "Pegawai {$pegawai['nama']} created.<br>";
            } else {
                echo "Pegawai {$pegawai['nama']} already exists.<br>";
            }
        }

        // Create sample jasa bonus data
        $users = $this->User_model->get_users_by_role('pegawai');
        
        foreach ($users as $user) {
            // Create jasa bonus for September 2025
            $jasa_data = array(
                'user_id' => $user->id,
                'periode' => '2025-09-01',
                'terima_sebelum_pajak' => 2500000,
                'pajak_5' => 125000,
                'pajak_15' => 0,
                'pajak_0' => 0,
                'terima_setelah_pajak' => 2375000
            );
            
            if (!$this->Jasa_bonus_model->period_exists_for_user($user->id, '2025-09-01')) {
                $this->Jasa_bonus_model->create_jasa_bonus($jasa_data);
                echo "Jasa bonus September 2025 created for {$user->nama}.<br>";
            }

            // Create jasa bonus for August 2025
            $jasa_data2 = array(
                'user_id' => $user->id,
                'periode' => '2025-08-01',
                'terima_sebelum_pajak' => 2200000,
                'pajak_5' => 110000,
                'pajak_15' => 0,
                'pajak_0' => 0,
                'terima_setelah_pajak' => 2090000
            );
            
            if (!$this->Jasa_bonus_model->period_exists_for_user($user->id, '2025-08-01')) {
                $this->Jasa_bonus_model->create_jasa_bonus($jasa_data2);
                echo "Jasa bonus August 2025 created for {$user->nama}.<br>";
            }
        }

        echo "<br>Sample data creation completed!<br>";
        echo "<a href='" . base_url('auth/login') . "'>Go to Login</a><br>";
        echo "Admin Login: admin / admin123<br>";
        echo "Pegawai Login: johndoe / pegawai123<br>";
    }
}