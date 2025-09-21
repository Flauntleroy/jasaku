<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<aside
  :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
  class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
>
  <!-- Sidebar Header -->
  <div :class="sidebarToggle ? 'justify-center' : 'justify-between'" class="flex items-center gap-2 pt-8 sidebar-header pb-7">
    <a href="<?= base_url('admin/dashboard') ?>">
      <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
        <img class="dark:hidden" src="<?= base_url('tailadmin-free-tailwind-dashboard-template-main/build/src/images/logo/logo.svg') ?>" alt="Logo" onerror="this.style.display='none'" />
        <img class="hidden dark:block" src="<?= base_url('tailadmin-free-tailwind-dashboard-template-main/build/src/images/logo/logo-dark.svg') ?>" alt="Logo" onerror="this.style.display='none'" />
      </span>
      <img class="logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'" src="<?= base_url('tailadmin-free-tailwind-dashboard-template-main/build/src/images/logo/logo-icon.svg') ?>" alt="Logo" onerror="this.style.display='none'" />
    </a>
  </div>

  <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
    <!-- Sidebar Menu -->
    <nav>
      <?php $role = $this->session->userdata('role'); ?>
      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span :class="sidebarToggle ? 'lg:hidden' : ''">Menu</span>
        </h3>
        <ul class="flex flex-col gap-4 mb-6">
          <?php if ($role === 'admin'): ?>
            <li>
              <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= uri_string()==='admin/dashboard'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= uri_string()==='admin/users'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Kelola Pegawai</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/jasa-bonus') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= (uri_string()==='admin/jasa-bonus'||uri_string()==='admin/jasa')?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Data Jasa/Bonus</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/laporan') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= uri_string()==='admin/laporan'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Laporan</span>
              </a>
            </li>
          <?php else: ?>
            <li>
              <a href="<?= base_url('pegawai/dashboard') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= uri_string()==='pegawai/dashboard'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('pegawai/history') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 <?= uri_string()==='pegawai/history'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <span>Riwayat TTD</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400"><span :class="sidebarToggle ? 'lg:hidden' : ''">Lainnya</span></h3>
        <ul class="flex flex-col gap-4 mb-6">
          <li>
            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
              <span>Keluar</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Sidebar Menu -->
  </div>
</aside>
