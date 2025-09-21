<?php
// Script untuk generate password hash yang benar
echo "Generating password hashes...\n";

$admin_password = 'admin123';
$pegawai_password = 'pegawai123';

$admin_hash = password_hash($admin_password, PASSWORD_DEFAULT);
$pegawai_hash = password_hash($pegawai_password, PASSWORD_DEFAULT);

echo "Admin password: $admin_password\n";
echo "Admin hash: $admin_hash\n\n";

echo "Pegawai password: $pegawai_password\n";
echo "Pegawai hash: $pegawai_hash\n\n";

// Verification
echo "Verification:\n";
echo "Admin verify: " . (password_verify($admin_password, $admin_hash) ? "OK" : "FAIL") . "\n";
echo "Pegawai verify: " . (password_verify($pegawai_password, $pegawai_hash) ? "OK" : "FAIL") . "\n";
?>