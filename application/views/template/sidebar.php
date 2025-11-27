<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<aside
  :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
  class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
>
  <!-- Sidebar Header -->
  <div :class="sidebarToggle ? 'justify-center' : 'justify-between'" class="flex items-center gap-2 pt-8 sidebar-header pb-7">
    <?php $roleHeader = $this->session->userdata('role'); $homeUrl = $roleHeader === 'admin' ? 'admin/dashboard' : 'pegawai/dashboard'; ?>
    <a href="<?= base_url($homeUrl) ?>" class="flex items-center gap-3">
      <img src="<?= base_url('assets/images/logo/jasaku.png') ?>" alt="Jasa-Ku" class="rounded-lg" style="width: 40px; height: 40px; object-fit: contain;" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo/logo-icon.svg') ?>'" />
      <span class="text-lg font-semibold text-gray-900 dark:text-white" :class="sidebarToggle ? 'lg:hidden' : ''">Jasa-Ku</span>
    </a>
  </div>

  <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
    <!-- Sidebar Menu -->
    <nav>
      <?php $role = $this->session->userdata('role'); ?>
      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400 ">
          <span :class="sidebarToggle ? 'lg:hidden' : ''">Menu</span>
        </h3>
        <ul class="flex flex-col gap-4 mb-6">
          <?php if ($role === 'admin'): ?>
            <li>
              <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='admin/dashboard'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ' dark:text-white'" >Dashboard</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='admin/users'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.67 0-8 1.34-8 4v2h10v-2c0-2.66-5.33-4-8-4zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45v2H24v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Kelola Pegawai</span>
              </a>
            </li>
            <!-- <li>
              <a href="<?= base_url('admin/jasa-bonus') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= (uri_string()==='admin/jasa-bonus'||uri_string()==='admin/jasa')?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17h18v2H3v-2zm0-5h18v2H3v-2zm0-5h18v2H3V7z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Data Jasa/Bonus</span>
              </a>
            </li> -->
            <!-- <li>
              <a href="<?= base_url('admin/laporan') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='admin/laporan'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm2 4h14v12H5V7zm4 2v8h2V9H9zm4 0v8h2V9h-2z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Laporan</span>
              </a>
            </li> -->
            <li>
              <a href="<?= base_url('admin/maintenance') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='admin/maintenance'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M22 7l-9 9-4-4-6 6 2 2 6-6 4 4 11-11z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Maintenance</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/notifikasi') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='admin/notifikasi'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2zm6-6V11a6 6 0 1 0-12 0v5l-2 2v1h16v-1l-2-2z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Push Notifikasi</span>
              </a>
            </li>
          <?php else: ?>
            <li>
              <a href="<?= base_url('pegawai/dashboard') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='pegawai/dashboard'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('pegawai/tanda-tangan') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='pegawai/tanda-tangan'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17h18v2H3v-2zm4-3h10v2H7v-2zm-4-4h18v2H3V10zm0-5h12v2H3V5z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Tanda Tangan</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('pegawai/history') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='pegawai/history'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8V4l8 8-8 8v-4H4V8h8z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">History</span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('pegawai/profil') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5 <?= uri_string()==='pegawai/profil'?'bg-gray-100 dark:bg-white/5 font-medium':'' ?>">
                <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span :class="sidebarToggle ? 'lg:hidden' : ''">Profil</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-white"><span :class="sidebarToggle ? 'lg:hidden' : ''">Lainnya</span></h3>
        <ul class="flex flex-col gap-4 mb-6">
          <li>
            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5">
              <svg class="h-5 w-5 text-gray-500 dark:text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M10 17l5-5-5-5v3H3v4h7v3zm9-12H12V3h7c1.1 0 2 .9 2 2v14a2 2 0 0 1-2 2h-7v-2h7V5z"/></svg>
              <span :class="sidebarToggle ? 'lg:hidden' : ''">Keluar</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Sidebar Menu -->
  </div>
</aside>
