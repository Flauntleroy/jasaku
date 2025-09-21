<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Profil Saya</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Perbarui informasi akun Anda dan ubah password.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('pegawai/dashboard') ?>">Dashboard</a>
      </li>
    </ol>
  </nav>
</div>

<?php if ($this->session->flashdata('success')): ?>
  <div class="mb-4 rounded-xl bg-emerald-50 p-4 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"> <?= $this->session->flashdata('success') ?> </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="mb-4 rounded-xl bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $this->session->flashdata('error') ?> </div>
<?php endif; ?>

<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12 lg:col-span-7">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
      <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Profil</h3>
      <form method="post" action="<?= base_url('pegawai/profil/simpan') ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-sm text-gray-600">NIK</label>
          <input type="text" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" value="<?= html_escape($user->nik) ?>" disabled />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">Username</label>
          <input name="username" type="text" class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" value="<?= html_escape($user->username) ?>" disabled />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">Nama</label>
          <input name="nama" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" value="<?= html_escape($user->nama) ?>" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">Ruangan</label>
          <input name="ruangan" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" value="<?= html_escape($user->ruangan) ?>" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">No. HP</label>
          <input name="phone" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" value="<?= html_escape($user->phone) ?>" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">No. Rekening</label>
          <input name="no_rekening" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" value="<?= html_escape($user->no_rekening) ?>" />
        </div>
        <div class="sm:col-span-2">
          <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 border border-primary" style="background-color:#2563eb;border-color:#2563eb;">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
  <div class="col-span-12 lg:col-span-5">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
      <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Ubah Password</h3>
      <form method="post" action="<?= base_url('pegawai/profil/ubah-password') ?>" class="grid grid-cols-1 gap-4">
        <div>
          <label class="mb-1 block text-sm text-gray-600">Password Lama</label>
          <input name="password_lama" type="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">Password Baru</label>
          <input name="password_baru" type="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" minlength="6" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600">Konfirmasi Password Baru</label>
          <input name="password_baru_konfirmasi" type="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900" minlength="6" required />
        </div>
        <div>
          <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 border border-primary" style="background-color:#2563eb;border-color:#2563eb;">Simpan Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
