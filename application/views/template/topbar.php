<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Header -->
<header x-data="{menuToggle: false}" class="sticky top-0 z-99999 flex w-full border-gray-200 bg-white lg:border-b dark:border-gray-800 dark:bg-gray-900">
  <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
    <div class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4 dark:border-gray-800">
      <!-- Hamburger Toggle BTN -->
      <button :class="sidebarToggle ? 'lg:bg-transparent dark:lg:bg-transparent bg-gray-100 dark:bg-gray-800' : ''" class="z-99999 flex h-10 w-10 items-center justify-center rounded-lg border-gray-200 text-gray-500 lg:h-11 lg:w-11 lg:border dark:border-gray-800 dark:text-gray-400" @click.stop="sidebarToggle = !sidebarToggle">
        <!-- Icon -->
        <svg class="hidden fill-current lg:block" width="20" height="14" viewBox="0 0 20 14" xmlns="http://www.w3.org/2000/svg"><path d="M18.3334 0.333374H1.66669C1.22466 0.333374 0.800739 0.508963 0.48818 0.821522C0.17562 1.13408 0 1.55799 0 2.00004C0 2.44207 0.17562 2.866 0.48818 3.17856C0.800739 3.49112 1.22466 3.66671 1.66669 3.66671H18.3334C18.7754 3.66671 19.1994 3.49112 19.5119 3.17856C19.8245 2.866 20.0001 2.44207 20.0001 2.00004C20.0001 1.55799 19.8245 1.13408 19.5119 0.821522C19.1994 0.508963 18.7754 0.333374 18.3334 0.333374Z"/><path d="M18.3334 5.66663H1.66669C1.22466 5.66663 0.800739 5.84222 0.48818 6.15478C0.17562 6.46734 0 6.89126 0 7.33329C0 7.77532 0.17562 8.19924 0.48818 8.5118C0.800739 8.82436 1.22466 8.99995 1.66669 8.99995H18.3334C18.7754 8.99995 19.1994 8.82436 19.5119 8.5118C19.8245 8.19924 20.0001 7.77532 20.0001 7.33329C20.0001 6.89126 19.8245 6.46734 19.5119 6.15478C19.1994 5.84222 18.7754 5.66663 18.3334 5.66663Z"/><path d="M18.3334 11H1.66669C1.22466 11 0.800739 11.1756 0.48818 11.4882C0.17562 11.8007 0 12.2247 0 12.6667C0 13.1087 0.17562 13.5326 0.48818 13.8452C0.800739 14.1577 1.22466 14.3333 1.66669 14.3333H18.3334C18.7754 14.3333 19.1994 14.1577 19.5119 13.8452C19.8245 13.5326 20.0001 13.1087 20.0001 12.6667C20.0001 12.2247 19.8245 11.8007 19.5119 11.4882C19.1994 11.1756 18.7754 11 18.3334 11Z"/></svg>
      </button>
      <!-- Brand on mobile -->
      <a href="<?= base_url('admin/dashboard') ?>" class="lg:hidden inline-flex items-center gap-2">
        <img src="<?= base_url('assets/images/logo/jasaku.png') ?>" alt="Jasa-Ku" class="h-8 w-8 rounded-md object-contain" onerror="this.style.display='none'" />
        <span class="text-base font-semibold text-gray-900 dark:text-white">Jasa-Ku</span>
      </a>

      <div class="hidden lg:block">
        <form>
          <!-- Optional search or actions -->
        </form>
      </div>
    </div>

    <div :class="menuToggle ? 'flex' : 'hidden'" class="shadow-theme-md w-full items-center justify-between gap-4 px-5 py-4 lg:flex lg:justify-end lg:px-0 lg:shadow-none">
      <div class="2xsm:gap-3 flex items-center gap-2">
  <!-- Dark Mode Toggler -->
        <button @click.prevent="darkMode = !darkMode" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
          <svg class="block dark:hidden" width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><path d="M11 0C13.6193 0 16.0928 1.04107 17.9497 2.89797C19.8066 4.75488 20.8477 7.22831 20.8477 9.84766C20.8477 14.4004 17.1328 18.1152 12.5801 18.1152C7.93359 18.1152 4.21875 14.4004 4.21875 9.84766C4.21875 6.15938 6.27207 2.92188 9.3457 1.25781C9.49323 1.17578 9.66038 1.14551 9.82617 1.17383C9.99196 1.20215 10.1455 1.28753 10.2617 1.41602C10.3779 1.54451 10.4498 1.70827 10.4668 1.88281C10.4839 2.05735 10.4455 2.2339 10.3574 2.38867C9.41992 4.03125 8.96094 5.92969 9.03906 7.83594C9.11719 9.74219 9.72656 11.5742 10.791 13.1035C11.8555 14.6328 13.3359 15.7871 15.0391 16.4277C16.7422 17.0684 18.6006 17.1719 20.3691 16.7266C20.5312 16.6855 20.7021 16.7041 20.8545 16.78C21.0068 16.8559 21.1328 16.9845 21.208 17.1465C21.2832 17.3086 21.3037 17.4941 21.2656 17.6719C20.7373 20.1484 19.3887 22.417 17.3965 24.0215C15.4043 25.626 12.8643 26.4688 10.25 26.4688C4.58984 26.4688 0 21.8789 0 16.2188C0 13.5994 1.04107 11.126 2.89797 9.26908C4.75488 7.41218 7.22831 6.37109 9.84766 6.37109C11 6.37109 11 6.37109 11 6.37109Z"/></svg>
        </button>

        <span class="hidden sm:block text-sm text-gray-600 dark:text-gray-400"><?= $this->session->userdata('nama') ?></span>
        <a href="<?= base_url('auth/logout') ?>" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/5">Keluar</a>
      </div>
    </div>
  </div>
</header>
